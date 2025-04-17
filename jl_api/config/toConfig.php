<?php
return [
    'goods_image_url'=>env('IMAGE.GOODSIMAGEURL','http://jlapi.yulecheng.icu/static'),//image 图片地址(商品的图片地址)# 后端地址(商品图片，banner)
    'qrcode_url'=>env('IMAGE.QRCODEURL',''),//二维码显示域名(二维码生成保存的地址)#  前端地址
    'invite_pay_url'=>env('IMAGE.INVITEPAYURL',''),//邀请付款地址。就是订单付款页面 的地址，前端
];