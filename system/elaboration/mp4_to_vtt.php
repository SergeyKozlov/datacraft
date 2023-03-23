<?php

use model\PostgreSQL;

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
$welcome = new NAD();
$pg = new PostgreSQL();
$s3 = new S3();
$ffmpegConv = new FfmpegConv();
$fs = new FileSteward();
$sendmail = new sendmail();


//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

//$pgGetItemsNoPreVTT['table'] = $_REQUEST['table'];
//$pgGetItemsNoPreVTT['limit'] = $welcome->setLimit();

$all_data = $pg->pgGetItemsNoPreVTT();
echo "\n\rpgGetItemsNoPreVTT --------------\n\r";
print_r($all_data);
//exit();
/*foreach ($all_data as $key => $value) {
    echo "\n\rforeach value created_at --------------\n\r";
    print_r(key($value));
    print_r($value['created_at']);
    $trueDate = strtotime($value['created_at']);
    if ($trueDate < strtotime('2018-06-24')) {
        echo " <<<<<<<<<<<<<<<<";
        echo "\n\rforeach value table --------------\n\r";
        //echo "\n\r" . $_GET['table'] . $_GET['key'] . $value["$keyU"] . "\n\r";
        //print_r($pg->pgDelete($_REQUEST['table'], 'count_item_id', $value['count_item_id']));
        //print_r($pg->pgDelete($pg->table_posts, 'post_id', $value['post_id']));
    }
}*/

if (!empty($all_data[0]['src'])) {
    echo "\n\rpgGetItemsNoPreVTT -------------- src \n\r" . $all_data[0]['src'];
    $item_id = json_decode($all_data[0]['src']);
    /*} else {
        echo "\n\rpgGetItemsNoPreVTT -------------- src empty\n\r";
        $all_data[0]['src'] = $all_data[0]['item_id'] . '-240.mp4';
        $item_id[0] = $all_data[0]['item_id'] . '-240.mp4';
    }*/

    echo "\n\rpgGetItemsNoPreVTT -------------- file name: \n\r" . $item_id[0];
    //file_get_contents('https://s3.amazonaws.com/video.vide.me/' . $item_id[0] . '-240.mp4');
    //$welcome->use_curl('https://s3.amazonaws.com/video.vide.me/' . $item_id[0] . '-240.mp4', $item_id[0]);
    $s3->downloadVideo($item_id[0]);
    /**********************************************/
    $fileToPre = $ffmpegConv->fileTo_pre_video_image_sprite(['file' => $all_data[0]['item_id']]);
    if ($fileToPre) {
        echo "\n\rmp4_to_vtt fileTo_pre_video_image_sprite fileToPre\n\r";
        print_r($fileToPre);
        $video_info['video_duration'] = $ffmpegConv->getVideoDuration($welcome->nadtemp . $item_id[0]);
        echo "\n\rmp4_to_vtt video_info\n\r";
        print_r($video_info);
        $fileToS3_pre = $fs->fileToS3_pre_video_image_sprite(['file' => $all_data[0]['item_id']], $video_info);
        if ($fileToS3_pre) {
            //$this->taskChangeStatus($lastTask, "success");
            echo "\n\rmp4_to_vtt fileToS3_pre_video_image_sprite fileToS3_pre\n\r";
            print_r($fileToS3_pre);

            /* Update item src ********************************   */
            //$itemInfo= $pg->pgGetItemFullInfo($lastTask["task_item_id"]);
            $itemTrue = $pg->pgOneDataByColumn([
                'table' => $pg->table_items,
                'find_column' => 'item_id',
                'find_value' => $all_data[0]['item_id']]);
            //echo "\n\rpgSchedulerWork fileUploadVideoMP4_240 itemInfo\n\r";
            //print_r($itemInfo);
            //$itemTrue = $pg->pgPaddingItems($itemInfo);
            $itemTrue['pre_v_w320'] = '1';
            $itemTrue['pre_i_w320'] = '1';
            $itemTrue['spr_w120'] = '1';
            $itemTrue['vtt_w120'] = '1';


            /*if ($video_info['video_duration'] > 29) {
                $itemTrue['thumb_s_56w120'] = '1';
            }*/
            //$itemTrue['src'] = json_encode([0 => $lastTask["task_item_id"] . '-240.' . $path_parts['extension']]);
            echo "\n\rmp4_to_vtt fileToS3_pre_video_image_sprite itemTrue\n\r";
            print_r($itemTrue);
            $pg->pgUpdateDataArray($pg->table_items, $itemTrue, ['item_id' => $all_data[0]['item_id']]);
            $sendmail->SendStaffAlert(['message' => "mp4_to_vtt success file: " . $all_data[0]['item_id'] . ' title: ' . $all_data[0]['title'] . ' created_at: ' . $all_data[0]['created_at']]);
            unlink($welcome->nadtemp . $item_id[0]);
        } else {
            // Convert failure
            //echo "\nmp4_to_vtt fileToS3_pre_video_image_sprite failure\n\r";
            echo "\n\r======================================================\n\r";
            echo "\n\rmp4_to_vtt fileToS3_pre_video_image_sprite failure: \n\r" . $all_data[0]['item_id'];
            echo "\n\r======================================================\n\r";
            print_r($fileToS3_pre);
            $sendmail->SendStaffAlert(['message' => "mp4_to_vtt fileToS3_pre_video_image_sprite failure : " . $all_data[0]['item_id']]);
            //$this->taskChangeStatus($lastTask, "error");
            exit();
        }
    } else {
        // Convert failure
        //echo "\nmp4_to_vtt fileCreate_pre_video_image_sprite failure\n\r";
        //$this->taskChangeStatus($lastTask, "error");
        echo "\n\r======================================================\n\r";
        echo "\n\rmp4_to_vtt fileTo_pre_video_image_sprite failure: \n\r" . $all_data[0]['item_id'];
        echo "\n\r======================================================\n\r";
        print_r($fileToPre);
        $sendmail->SendStaffAlert(['message' => "mp4_to_vtt fileTo_pre_video_image_sprite failure : " . $all_data[0]['item_id']]);
        exit();
    }
    /**********************************************/
} else {
    echo "\n\r======================================================\n\r";
    echo "\n\rempty(all_data[0]['src: \n\r" . $all_data[0]['item_id'];
    echo "\n\r======================================================\n\r";
    $sendmail->SendStaffAlert(['message' => "empty(all_data[0]['src : " . $all_data[0]['item_id']]);
}
echo "\n\r--------------\n\r";