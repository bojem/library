<?php
namespace q;

class Captcha
{
    private $width;
    private $height;
    private $type;
    private $len;
    private $fontsize;
    private $font;

    public function __construct($width=68, $height=28, $type=1, $len=4, $fontsize=14)
    {
        $this->width = $width;
        $this->height = $height;
        $this->type = $type;
        $this->len = $len;
        $this->fontsize = $fontsize;
        $this->font = dirname(__FILE__) . "/image/font/Microsoft.ttf";;
    }

    /**
     * 生成验证码数字
     * @return false|string
     */
    public function code() {
        if($this->type == 1){
            $chars="23456789";
        }elseif($this->type == 2){
            $chars="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }else{
            $chars="ABCDEFGHIJKLMNOPQRSTUVWXYZ23456789";
        }
        $chars = str_shuffle($chars);
        $code = substr($chars, 0, $this->len);
        return $code;
    }

    public function create($code){
        //创建画布并填充
        $this->width     = ($this->len * 10 + 10) > $this->width ? $this->len * 10 + 10 : $this->width;
        $im        = imagecreatetruecolor($this->width, $this->height);
        $bkcolor   = imagecolorallocate($im, mt_rand(250, 255), mt_rand(250, 255), mt_rand(250, 255));   //背景色
        $linecolor = imagecolorallocate($im, mt_rand(210, 235), mt_rand(210, 235), mt_rand(210, 235)); //背景线色
        imagefill($im, 0, 0, $bkcolor);

        //画背景线
        for($i = 6; $i < $this->width; $i=$i+6){
            imageline($im, $i, 0, $i, $this->height, $linecolor);
        }
        for($i = 6; $i < $this->width; $i=$i+6){
            imageline($im, 0, $i, $this->width, $i, $linecolor);
        }

        //写文字
        $x =($this->width-$this->width*0.1) / $this->len;
        $y =($this->height)/2+$this->fontsize/2;
        for($i = 0; $i < $this->len; $i++){
            $fontcolor = imagecolorallocate($im, mt_rand(0, 150), mt_rand(0, 150), mt_rand(0, 150));         //字体色
            imagettftext($im, $this->fontsize, mt_rand(- 30, 30), $x * $i +$this->width*0.06 , $y, $fontcolor, $this->font, $code[$i]);
        }

        //画干扰线
        $R = mt_rand(10, $y);
        $X = mt_rand(15, 25);
        $Y = mt_rand(5, 10);
        $L = mt_rand(50, 80);
        for($yy = $R; $yy <= $R + 1; $yy++){
            for($px = -$L; $px <= $L; $px = $px + 0.1){
                $x = $px/$X;
                if($x != 0) $y = sin($x);
                $py = $y*$Y;
                imagesetpixel($im, $px + $L, $py + $yy, $fontcolor);
            }
        }
        ob_start();
        imagepng($im);
        $result = ob_get_contents();
        ob_end_clean();
        imagedestroy($im);
        return ['image' => 'data:image/png;base64,' . base64_encode($result)];
    }



}
