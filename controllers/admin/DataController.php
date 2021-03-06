<?php

namespace app\controllers\admin;

use app\models\Comparison;
use app\models\Mailbox;
use Yii;
use yii\web\UploadedFile;

class DataController extends BaseController
{
    private $text = ['txt'];

    public function actionIndex()
    {
        $model = Comparison::findOne(['type'=>1]);
        return $this->render('index',['data'=>$model]);
    }

    public function actionUpload(){
        $this->checkFolder();
        $file = UploadedFile::getInstanceByName('file');
        if($file && $this->validFile($file)){
            $type = Yii::$app->request->post('type');
            $uploadFolder = $this->checkFolder();
            $fileName = 'data'.$type. '.' . $file->extension;
            if(file_exists($uploadFolder.$fileName)){
                unlink($uploadFolder.$fileName);
            }
            $file->saveAs($uploadFolder . $fileName);
            $state = $this->readTxt($uploadFolder . $fileName);
            return json_encode(['state'=>$state,'msg'=>!$state ? '数据更新失败,文本数据格式不正确' : null]);
        }else{
            return json_encode(['state'=>false,'msg'=>'上传失败']);
        }
    }

    private function readTxt($txtUrl){
        if(file_exists($txtUrl)){
            $content = file_get_contents($txtUrl);
            $content = str_replace("\r\n", ' ', $content); //把换行符 替换成空格
            $contentArr = explode(' ',$content);
            $contentArr = array_filter($contentArr);
            $txt = true;
            foreach($contentArr as $key=>$val){
                if(!intval($val)){
                    $txt = false;
                    break;
                }
            }
            if(!$txt){
                return false;
            }
            Comparison::deleteAll(['type'=>Yii::$app->request->post('type')]);
            $model = new Comparison();
            $model->txt = file_get_contents($txtUrl);
            $model->type = Yii::$app->request->post('type');
            $model->time = time();
            $model->save();
            return true;
        }
    }

    /*
     * 检查 用户上传是否是Image
     * */
    private function validFile($file){
        return in_array($file->extension,$this->text);
    }

    /*
     * 检查文件夹是否存在
     * */
    private function checkFolder(){
        $uploadFolder =  Yii::getAlias('@webroot').'/upload';
        if(!file_exists($uploadFolder)){
            mkdir($uploadFolder);
        }
        $uploadFolder = $uploadFolder . '/data';
        if(!file_exists($uploadFolder)){
            mkdir($uploadFolder);
        }
        return $uploadFolder . '/';
    }

}
