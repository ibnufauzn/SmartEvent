import argparse
import time
from os import path
import os
import configparser
import datetime
import csv

import numpy as np
import pandas as pd
import requests
import cv2
import mysql.connector
import getpass
from playsound import playsound

#Koneksi ke Database
connection = mysql.connector.connect(host='iix60.idcloudhost.com',user='iotsmart_admin', password='F.M%OVIGjl^t', database='iotsmart_camera')
cursor = connection.cursor()

#Validasi Login
emailPanitia = input("Masukkan Email Panitia : ")
passwordPanitia = getpass.getpass("Masukkan Password Panitia : ")
periksaData = "SELECT * FROM akunpanitia WHERE email_panitia=%s AND password_panitia=%s"
cursor.execute(periksaData, [(emailPanitia), (passwordPanitia)])
ambilDataPanitia = cursor.fetchall()
if len(ambilDataPanitia) != 0:
    print('Berhasil Login')
    print("=========================")
else:
    print('Email Atau Password Salah')
    exit()

#Inisialisasi argumen untuk menjalankan program
def define_args():
    ap = argparse.ArgumentParser()
    ap.add_argument("-c", "--config", required=True, help="Configuration file")
    return vars(ap.parse_args())

#Luas Ruangan
panjangRuangan = int(input("[REQ] Masukkan Panjang Ruangan (m) : "))
lebarRuangan = int(input("[REQ] Masukkan Lebar Ruangan (m) : "))
luasRuangan = panjangRuangan * lebarRuangan

#Kapasitas Ruangan
kapasitas = int(input("[REQ] Masukkan Kapasitas Ruangan Yang Diperbolehkan : "))
print("=========================")

#Inisialisasi Fungsi Untuk Membaca Konfigurasi File
def read_config(filename):
    print("[INFO] Membaca Config : {}".format(filename))
    if not os.path.isfile(filename):
        print("[ERROR] File Config \"{}\" Tidak Ditemukan.".format(filename))
        exit()
    cfg = configparser.ConfigParser()
    cfg.read(filename)
    return cfg

#Menyimpan file yang berisi waktu deteksi dan jumlah orang terdeteksi
def save_count(filename, n):
    f = open(filename, "a")
    timestamp = datetime.datetime.now().strftime("%Y%m%d_%H-%M-%S")
    line = "{} , {}\n".format(timestamp, n)
    f.write(line)
    f.close()

#Membaca data dari file
def read_existing_data(filename):
    times = []
    values = []
    if os.path.isfile(filename):
        with open(filename) as csvfile:
            csv_reader = csv.reader(csvfile, delimiter=',')
            for row in csv_reader:
                times.append(datetime.datetime.strptime(row[0], "%Y%m%d_%H-%M-%S "))
                values.append(int(row[1]))
    dataframe = pd.DataFrame()
    dataframe['timestamp'] = pd.Series(dtype='datetime64[ns]')
    dataframe['value'] = pd.Series(dtype=np.int32)
    dataframe['timestamp'] = times
    dataframe['value'] = values
    dataframe.set_index('timestamp', inplace=True)
    return dataframe

def blur_area(image, top_x, top_y, w, h):
    """
     Blur the specified area of the frame.
     Blurred area = <x,y> - <x+w, y+h>
     :type image: RGB array
     :type top_x: int
     :type top_y: int
     :type w: int
     :type h: int
    """
    # get the rectangle img around all the faces and apply blur
    sub_frame = image[top_y:top_y+h, top_x:top_x+w]
    sub_frame = cv2.GaussianBlur(sub_frame, (31, 31), 30)
    # merge back into the frame
    image[top_y:top_y+sub_frame.shape[0], top_x:top_x+sub_frame.shape[1]] = sub_frame
    return image

# Melakukan cnn dari frame video
def execute_network(image, network, layernames):
    blob = cv2.dnn.blobFromImage(image, 1 / 255.0, (416, 416), swapRB=True, crop=False)
    start2 = time.time()
    network.setInput(blob)
    outputs = network.forward(layernames)
    end2 = time.time()
    print("[INFO] Proses YOLO                 : %2.1f detik" % (end2-start2))
    return outputs

# Mengambil file YOLO
def load_network(network_folder):
    labelspath = os.path.sep.join([network_folder, "coco.names"])
    if not os.path.isfile(labelspath):
        print("[ERROR] Network: File Labels \"{}\" Tidak Ditemukan.".format(labelspath))
        exit()

    weightspath = os.path.sep.join([network_folder, "yolov3.weights"])
    if not os.path.isfile(weightspath):
        print("[ERROR] Network: File Weights \"{}\" Tidak Ditemukan.".format(weightspath))
        exit()

    configpath = os.path.sep.join([network_folder, "yolov3.cfg"])
    if not os.path.isfile(configpath):
        print("[ERROR] Network: File Configuration \"{}\" Tidak Ditemukan.".format(configpath))
        exit()

    # Load hasil training YOLO
    print("[INFO] Load YOLO dari penyimpanan...")

    # Mengambil label yang diinginkan untuk dideteksi
    labels = open(labelspath).read().strip().split("\n")

    # Menyimpan network kedalam format Darknet
    network = cv2.dnn.readNetFromDarknet(configpath, weightspath)
    names = network.getLayerNames()
    names = [names[i - 1] for i in network.getUnconnectedOutLayers()]
    return network, names, labels

def get_detected_items(layeroutputs, confidence_level, threshold, img_width, img_height):
    # Inisialisasi lists dari deteksi bounding box, confidences, dan class ID
    detected_boxes = []
    detection_confidences = []
    detected_classes = []

    for output in layeroutputs:
        # Looping setiap mendeteksi
        for detection in output:
            # Ekstrak Class ID dan confidence dari deteksi objek yang dilakukan
            scores = detection[5:]
            classid = np.argmax(scores)
            confidence = scores[classid]

            # Mengeluarkan hasil prediksi tidak akurat dengan memastikan probabilitas pendeteksian lebih besar dari minimum probabilitas
            if confidence > confidence_level:
                # Melakukan scaling koordinat bounding box relatif terhadap ukuran gambar
                box = detection[0:4] * np.array([img_width, img_height, img_width, img_height])
                (center_x, center_y, width, height) = box.astype("int")

                # koordinat center (x, y) untuk memperoleh sudut kiri atas dari bounding box
                top_x = int(center_x - (width / 2))
                top_y = int(center_y - (height / 2))

                # perbarui daftar koordinat bounding box, confidences, and class IDs
                detected_boxes.append([top_x, top_y, int(width), int(height)])
                detection_confidences.append(float(confidence))
                detected_classes.append(classid)

    # menerapkan suppresion non-maxima untuk menekan bounding box yang lemah dan tumpang tindih
    indexes = cv2.dnn.NMSBoxes(detected_boxes, detection_confidences, confidence_level, threshold)
    return indexes, detected_classes, detected_boxes, detection_confidences

def get_videowriter(outputfile, width, height, frames_per_sec=30):
    """
    Create a writer for the output video
    """
    # Initialise the writer
    fourcc = cv2.VideoWriter_fourcc(*"MJPG")
    video_writer = cv2.VideoWriter(outputfile, fourcc, frames_per_sec, (width, height), True)
    return video_writer, frames_per_sec

def save_frame(video_writer, new_frame, count=1):
    """
    Save frame <count> times to file.
    :param video_writer: writer for target file
    :param new_frame: frame to write
    :param count: number of times to write the frame
    :return:
    """
    for _ in range(0, count):
        video_writer.write(new_frame)

def get_webcamesource(webcam_id, width=640, height=480):
    """
    Create a reader for the input video. Input can be a webcam
    or a videofile
    """
    print("[INFO] initialising video source...")
    video_device = cv2.VideoCapture(webcam_id)
    video_device.set(cv2.CAP_PROP_FRAME_WIDTH, width)
    video_device.set(cv2.CAP_PROP_FRAME_HEIGHT, height)
    (success, videoframe) = video_device.read()
    if not success:
        print("[ERROR] Could not read from webcam id {}".format(webcam_id))
    (height, width) = videoframe.shape[:2]
    print("[INFO] Frame W x H: {} x {}".format(width, height))
    print("=========================")
    return video_device, width, height


def get_filesource(filename):
    """
    Create a reader for the input video
    """
    print("[INFO] initialising video source : {}".format(filename))
    video_device = cv2.VideoCapture(filename)
    width = int(video_device.get(cv2.CAP_PROP_FRAME_WIDTH))
    height = int(video_device.get(cv2.CAP_PROP_FRAME_HEIGHT))
    print("[INFO] Frame W x H: {} x {}".format(width, height))
    return video_device, width, height


def update_frame(image, people_indxs, class_ids, detected_boxes, conf_levels, colors, labels,
                 show_boxes, blur, box_all_objects):
    """
    Add bounding boxes and counted number of people to the frame
    Return frame and number of people
    """
    # ensure at least one detection exists
    count_people = 0
    if len(people_indxs) >= 1:
        # loop over the indexes we are keeping
        for i in people_indxs.flatten():
            # extract the bounding box coordinates
            (x, y, w, h) = (detected_boxes[i][0], detected_boxes[i][1], detected_boxes[i][2], detected_boxes[i][3])

            if classIDs[i] == 0:
                count_people += 1
                # Blur, if required, people in the image
                if blur:
                    image = blur_area(image, max(x, 0), max(y, 0), w, h)

            # draw a bounding box rectangle and label on the frame
            if (show_boxes and classIDs[i] == 0) or box_all_objects:
                color = [int(c) for c in colors[class_ids[i]]]
                cv2.rectangle(image, (x, y), (x + w, y + h), color, 2)
                text = "{}: {:.2f}".format(labels[classIDs[i]], conf_levels[i])
                cv2.putText(image, text, (x, y - 5), cv2.FONT_HERSHEY_SIMPLEX, 0.5, color, 2)

    # write number of people in bottom corner
    text = "Persons: {}".format(count_people)
    cv2.putText(image, text, (10, image.shape[0] - 20), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 255), 2)
    return image, count_people

if __name__ == '__main__':
    # construct the argument parse and parse the arguments
    args = define_args()
    config = read_config(args["config"])

    # Load the trained network
    (net, ln, LABELS) = load_network(config['NETWORK']['Path'])

    # Initialise video source
    webcam = (config['READER']['Webcam'] == "yes")
    if webcam:
        cam_id = int(config['READER']['WebcamID'])
        cam_width = int(config['READER']['Width'])
        cam_height = int(config['READER']['Height'])
        (cam, W, H) = get_webcamesource(cam_id, cam_width, cam_height)
    else:
        (cam, cam_width, cam_height) = get_filesource(config['READER']['Filename'])

    # determine if we need to show the enclosing boxes, etc
    network_path = config['NETWORK']['Path']
    webcam = (config['READER']['Webcam'] == "yes")
    showpeopleboxes = (config['OUTPUT']['ShowPeopleBoxes'] == "yes")
    showallboxes = (config['OUTPUT']['ShowAllBoxes'] == "yes")
    blurpeople = (config['OUTPUT']['BlurPeople'] == "yes")
    realspeed = (config['OUTPUT']['RealSpeed'] == "yes")
    nw_confidence = float(config['NETWORK']['Confidence'])
    nw_threshold = float(config['NETWORK']['Threshold'])
    countfile = config['OUTPUT']['Countfile']
    save_video = (config['OUTPUT']['SaveVideo'] == "yes")
    buffer_size = int(config['READER']['Buffersize'])
    # initialize a list of colors to represent each possible class label
    np.random.seed(42)
    COLORS = np.random.randint(0, 255, size=(len(LABELS), 3), dtype="uint8")

    # Initialise video ouptut writer
    if save_video:
        (writer, fps) = get_videowriter(config['OUTPUT']['Filename'], cam_width, cam_height,
                                        int(config['OUTPUT']['FPS']))
    else:
        (writer, fps) = (None, 0)

    # Create output windows, but limit on 1440x810
    cv2.namedWindow('Video', cv2.WINDOW_NORMAL)
    cv2.resizeWindow('Video', min(cam_width, 1440), min(cam_height, 810))
    #cv2.resizeWindow('Video', min(cam_width, 640), min(cam_height, 360))
    cv2.moveWindow('Video', 0, 0)

    # loop while true
    while True:
        start = time.time()
        # read the next frame from the webcam
        # make sure that buffer is empty by reading specified amount of frames
        for _ in (0, buffer_size):
            (grabbed, frame) = cam.read()  
            # type: (bool, np.ndarray)
        if not grabbed:
            break
        # Feed frame to network
        layerOutputs = execute_network(frame, net, ln)
        # Obtain detected objects, including cof levels and bounding boxes
        (idxs, classIDs, boxes, confidences) = get_detected_items(layerOutputs, nw_confidence, nw_threshold,
                                                                  cam_width, cam_height)

        # Update frame with recognised objects
        frame, npeople = update_frame(frame, idxs, classIDs, boxes, confidences, COLORS, LABELS, showpeopleboxes,
                                      blurpeople, showallboxes)
        save_count(countfile, npeople)

        # Show frame with bounding boxes on screen
        cv2.imshow('Video', frame)
        # write the output frame to disk, repeat (time taken * 30 fps) in order to get a video at real speed
        if save_video:
            frame_cnt = int((time.time()-start)*fps) if webcam and realspeed else 1
            save_frame(writer, frame, frame_cnt)

        end = time.time()
        print("[INFO] Lama Pemroresan Frame       : %2.1f detik" % (end - start))
        print("[INFO] Jumlah Orang di Dalam Frame : {}".format(npeople))

        # Mengambil Screenshot
        ambilScreenshot = 0
        nama = 'foto/' + str(ambilScreenshot) + '.jpg'
        while path.exists(nama) :
            nama = 'foto/' + str(ambilScreenshot) + '.jpg'
            ambilScreenshot += 1
        cv2.imwrite(nama, frame)
        print("[INFO] Mengambil Screenshot")
        
        #Informasi jumlah orang dalam integer
        jmlOrang = int("{}".format(npeople))

        # Status Kerumunan
        if jmlOrang > kapasitas :
            statusKerumunan = "Tidak Aman"
            playsound("KerumunanTerdeteksi.mp3")
        elif jmlOrang == kapasitas :
            statusKerumunan = "Mulai Berkerumun"
        else :
            statusKerumunan = "Aman"

        #Mengirimkan informasi data
        masukkanData = "INSERT INTO dataruang (id_panitia, jumlah_orang, luas_ruang, kapasitas_ruang, status_ruang) VALUES (%s, %s, %s, %s, %s)"
        dataDikirimkan = (emailPanitia, jmlOrang, luasRuangan, kapasitas, statusKerumunan)
        cursor.execute(masukkanData, dataDikirimkan)
        connection.commit()

        # Memberhentikan program bila interrupt
        if cv2.waitKey(25) & 0xFF == ord('q'):
            break

        #Mengirim Gambar ke Database
        print("[INFO] Mengirimkan Data dan Screenshot ke Database")

        url = "https://iotsmartevent.my.id/foto/upload.php"

        files = {'file': open(nama, 'rb')}
        rq = requests.post(url = url, files = files)
        print("=========================")

    # release the file pointers
    print("[INFO] Memberhentikan program..")

    if save_video:
        writer.release()
    cam.release()
    cv2.destroyAllWindows()