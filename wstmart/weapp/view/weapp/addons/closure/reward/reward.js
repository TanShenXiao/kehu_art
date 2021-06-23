var http = require('../../../utils/request.js');
const app = getApp();
function rewardCarts(page,shopId,shopPrice) {
  var rewardCartIds =  page.data.rewardCartIds;
  var check = page.data.check;
  var promotion = page.data.promotion;
  var money = 0
  var num = page.data.num;
  var price = page.data.price;
  var goodsReward = [];
  var isFind = false;
  //筛选出合适的促销
  for (var cartId in rewardCartIds[shopId]['list']){
      if(rewardCartIds[shopId]['list'][cartId]){
         for (var r in rewardCartIds[shopId]['list'][cartId]) {
            var cid = rewardCartIds[shopId]['list'][cartId][r];
            if (check[shopId].list[cid] == 1) {
               goodsReward = promotion[shopId]['list'][cid]['data']['json'];
               money += price[shopId].list[cid] * num[cid];
               isFind = true;
            }
         } 
      }
  }
  if(!isFind)return 0;
  var discount = 0;
  var words = '';
  for (var reward=goodsReward.length-1;reward>=0;reward--) {
      if (money >= goodsReward[reward].orderMoney) {
          if (goodsReward[reward].favourableJson.chk0) {
             words = '，已减' + goodsReward[reward].favourableJson.chk0val + '元';
             discount =  goodsReward[reward].favourableJson.chk0val;
             break;
          } else {
             words = '，已满足促销条件';
          }
      } else {
          words = '，还差' + (goodsReward[reward].orderMoney - money) + '元';
      }
  }
  if(page.data.data[shopId].list[0].promotion.data){
      var rewardTitle = page.data.data[shopId].list[0].promotion.data.rewardTitle;
      page.data.data[shopId].promotion = {rewardTitle:rewardTitle,words:words};
  }
  return discount;
};
function initCarts(){
   app.bindHook('beforeStatCartMoney','reward.rewardCarts');
}
function initSettlement(data){
   for(var p in data.data.carts){
        if(data.data.carts[p]['list'][0].rewardResult)data.data.carts[p].promotion = data.data.carts[p]['list'][0].promotion.data.rewardTitle+''+data.data.carts[p]['list'][0].rewardText;
   }
}
function rewardGoods(data, cb) {
  http.Post("addon/reward-weapp-goodsDetail", { goodsId: data.goodsId, shopId: data.shopId}, function (res) {
    if (res.status == 1) {
      typeof cb == "function" && cb({ rewardData:res.data });
    }
  });
};
module.exports = {
  initSettlement:initSettlement,
  initCarts:initCarts,
  rewardCarts: rewardCarts,
  rewardGoods: rewardGoods
}