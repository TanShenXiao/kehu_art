var http = require('../../../utils/request.js');
const app = getApp();
var pifaGoods = null;
function initCarts(data) {
   //获取商品id列表
   var goodsIds = [];
   var carts = data.data.carts;
   for(var p in carts){
       for(var goods in data.data.carts[p]['list']){
           goodsIds.push(data.data.carts[p]['list'][goods]['goodsId']);  
       }
   }
   http.Post("addon/pifa-weapp-goodsPifa", { goodsIds:goodsIds.join(',')}, function (res) {
    if (res.status == 1) {
       app.bindHook('beforeStatGoodsMoney','pifa.pifaCarts');
       pifaGoods = res.data;
    }
  });
};
function pifaCarts(page,shopId,rowNo){
  if(!pifaGoods)return;
  var goods = page.data.data[shopId]['list'][rowNo];
  if(pifaGoods[goods.goodsId]){
     var goodsPrice = goods.defaultShopPrice;
     for (var i = 0;i < pifaGoods[goods.goodsId].pifa.length;i++) {
        if(goods.cartNum>=pifaGoods[goods.goodsId].pifa[i].buyNum){
            goodsPrice = goods.defaultShopPrice-pifaGoods[goods.goodsId].pifa[i].rebate;
        }
     }
     page.data.data[shopId]['list'][rowNo].shopPrice = goodsPrice;
     page.data.data[shopId]['list'][rowNo].specPrice = goodsPrice;
     page.data.price[shopId]['list'][goods.cartId] = goodsPrice;
   }
}
var pifaDatas = null;
function pifaGoodsBox(page,goods){
   var pifa = {};
   pifa.isPifa = pifaDatas.isPifa;
   pifa.pifa = [];
   for(var i=0;i<pifaDatas.pifa.length;i++){
       pifa.pifa.push({goodsPrice:goods.goodsPrice-pifaDatas.pifa[i].rebate,buyNum:pifaDatas.pifa[i].buyNum+page.data.data.goodsUnit});
   }
   page.setData({pifabox:pifa});
   return goods;
}
function initGoodsDetail(page,data) {
  http.Post("addon/pifa-weapp-goodsDetail", { goodsId: data.goodsId}, function (res) {
    if (res.status == 1) {
      app.bindHook('checkGoodsStock','pifa.pifaGoodsBox');
      pifaDatas = res.data.goods;
       pifaDatas.goodsUnit = page.data.data.goodsUnit;
      page.setData({pifa:pifaDatas});
    }
  });
};

module.exports = {
  initCarts: initCarts,
  pifaCarts:pifaCarts,
  initGoodsDetail: initGoodsDetail,
  pifaGoodsBox:pifaGoodsBox
}