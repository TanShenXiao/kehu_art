var http = require('../../utils/request.js');
var reward = require('../../addons/closure/reward/reward.js');
var WSTAddons ={'reward':reward}
var app = getApp();
Page({
  data: {
    data: [],
    goodsLogo: null,
    check: [],
    num:[],
    price:[],
    isCheck:0,
    totalMoney:0,
    addons:[],
    promotion:[],
    rewardCartIds:[],
    domain: app.globalData.domain
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function () {
    try{
      WSTAddons['pifa'] = require('../../addons/closure/pifa/pifa.js');
    }catch(e){}
    app.emptyHook('beforeStatCartMoney');
    app.emptyHook('beforeStatGoodsMoney');
    this.setData({
      goodsLogo: app.globalData.confInfo.goodsLogo
    })
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function (options) {
    app.editTabBar();
    if (app.globalData.tokenId == null) {
      wx.navigateTo({
        url: '../login/login'
      })
    } else {
      this.getData();
    }
    app.getCartNum();
  },
  //数据
  getData: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    var addons = app.globalData.confInfo.addons;
    wx.showLoading({ title: '加载中' });
    http.Post("weapp/carts/index", { tokenId: tokenId }, function (res) {
      if (res.status == 1) {
        if (addons.Reward == 1) {
            if(typeof(WSTAddons['reward']['initCarts'])=='function')WSTAddons['reward']['initCarts'](res,{ tokenId: tokenId});
        }
        if (addons.Pifa == 1) {
            if(typeof(WSTAddons['pifa']['initCarts'])=='function')WSTAddons['pifa']['initCarts'](res,{ tokenId: tokenId});
        }
        that.setData({
          data: res.data.carts,
          check: res.data.check,
          num: res.data.num,
          price: res.data.price,
          isCheck: res.data.isCheck,
          resourceDomain:app.globalData.resourceDomain,
          promotion:res.data.promotion,
          rewardCartIds: res.data.rewardCartIds,
          totalMoney: res.data.goodsTotalMoney.toFixed(2)
        })
        that.getStock();
      }else{
        that.setData({
          data: [],
          check: [],
          num: [],
          price: [],
          isCheck: 0,
          totalMoney: 0
        })
      }
      wx.hideLoading();
    });
  },
  //首页
  toIndex: function() {
    wx.navigateTo({
      url: '../index/index'
    })
  },
  //店铺
  toshops: function (e) {
    var shopId = e.currentTarget.dataset.shopid;
    if (shopId == 1) {
      wx.navigateTo({
        url: '../shop-self/shop-self'
      })
    } else {
      wx.navigateTo({
        url: '../shop-home/shop-home?shopId=' + shopId
      })
    }
  },
  //商品
  togoods: function (e) {
    var goodsId = e.currentTarget.dataset.goodsid;
    wx.navigateTo({
      url: '../goods-detail/goods-detail?goodsId=' + goodsId
    })
  },
  //计算总价
  price: function () {
    var that = this;
    var str = '';
    for (var p in that.data.data) {
      for (var i in that.data.data[p]['list']) {
          if(app.globalData.WSTHook['beforeStatGoodsMoney'].length>0){
            for(var hkey=0;hkey<app.globalData.WSTHook['beforeStatGoodsMoney'].length;hkey++){
                str = app.globalData.WSTHook['beforeStatGoodsMoney'][hkey].split('.');
                WSTAddons[str[0]][str[1]](that,p,i);
             }
          }
      }
    }
    var check = that.data.check;
    var num = that.data.num;
    var price = that.data.price;
    var check = that.data.check;
    var promotion = that.data.promotion;
    var rewardCartIds = that.data.rewardCartIds;
    var totalMoney = 0;
    var str;
    for (var p in price) {
      var shopPrice = 0;
      var totalPrice = 0;
      for (var i in price[p]['list']) {
          shopPrice += price[p]['list'][i] * num[i];
          if (check[p]['list'][i]==1)totalPrice += price[p]['list'][i] * num[i];
      }
      var minusMoney = 0;
      if(app.globalData.WSTHook['beforeStatCartMoney'].length>0){
        for(var hkey=0;hkey<app.globalData.WSTHook['beforeStatCartMoney'].length;hkey++){
           str = app.globalData.WSTHook['beforeStatCartMoney'][hkey].split('.');
           minusMoney = WSTAddons[str[0]][str[1]](that,p,shopPrice);
           totalPrice = totalPrice - minusMoney;
           shopPrice = shopPrice - minusMoney;
        }
      }
      
      price[p]['money'] = shopPrice.toFixed(2);
      totalMoney += totalPrice;
    }
    that.setData({
      data: that.data.data,
      price: price,
      totalMoney: totalMoney.toFixed(2)
    });
  },
  //库存判断
  getStock: function (e) {
    var that = this;
    var check = that.data.check;
    var num = that.data.num;
    var ids = [];
    for (var c in check) {
      for (var l in check[c]['list']) {
        if (num[l] > check[c]['stock'][l]) {
          check[c]['list'][l] = 0;
          ids += l + ',';
        }
      }
    }
    that.isCheck(ids, 0);
    that.price();
    that.setData({
      check: check
    });
  },
  //选择店铺
  shopCheck: function (e) {
    var that = this;
    var id = e.currentTarget.id;
    var types = e.currentTarget.dataset.types;
    var check = that.data.check;
    var num = that.data.num;
    var isCheck=1;
    var ids = [];
    var ids2 = [];
    types = (types == 1) ? 0 : 1;
    check[id]['isCheck']= types;
    for (var i in check[id]['list']) {
      if (num[i] > check[id]['stock'][i]){
        check[id]['list'][i] = 0;
        ids2 += i + ',';
      }else{
        check[id]['list'][i] = types;
        ids += i + ',';
      }
    }
    if (types==1){
      for (var c in check) {
        if (check[c]['isCheck'] == 0) isCheck = 0;
      }
    }else{
      isCheck = 0;
    }
    that.isCheck(ids, types);
    that.isCheck(ids2, 0);
    that.price();
    that.setData({
      check: check,
      isCheck: isCheck
    });
  },
  //选择商品
  goodsCheck: function (e) {
    var that = this;
    var id = e.currentTarget.id; 
    var shopid = e.currentTarget.dataset.shopid;
    var types = e.currentTarget.dataset.types;
    var check = that.data.check;
    var num = that.data.num;
    var isCheck = 1;
    var shopCheck = 1;
    types = (types == 1) ? 0 : 1;
    if (num[id] > check[shopid]['stock'][id]){
      types=0;
    }
    check[shopid]['list'][id] = types;
    if (types == 1) {
      for (var i in check[shopid]['list']) {
        if (check[shopid]['list'][i] == 0) shopCheck = 0;
        check[shopid]['isCheck'] = shopCheck;
      }
      for (var c in check) {
        if (check[c]['isCheck'] == 0) isCheck = 0;
      }
    } else {
      check[shopid]['isCheck'] = 0;
      isCheck = 0;
    }
    that.isCheck(id, types);
    that.price();
    that.setData({
      check: check,
      isCheck: isCheck
    });
  },
  //全选
  allCheck: function (e) {
    var that = this;
    var types = e.currentTarget.dataset.types;
    var check = that.data.check;
    var num = that.data.num;
    var isCheck = 1;
    var ids = [];
    var ids2 = [];
    types = (types == 1) ? 0 : 1;
    for (var c in check) {
      check[c]['isCheck'] = types;
      for (var i in check[c]['list']) {
        if (num[i] > check[c]['stock'][i]){
          check[c]['list'][i] = 0;
          ids2 += i + ',';
        }else{
          check[c]['list'][i] = types;
          ids += i + ',';
        }
      }
    }
    that.isCheck(ids, types);
    that.isCheck(ids2, 0);
    that.price();
    that.setData({
      check: check,
      isCheck: types
    });
  },
  //选中处理
  isCheck: function (ids, types) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/carts/batchSetIsCheck", { tokenId: tokenId, id: ids, isCheck: types }, function (res) {
    });
  },
  //数量变化
  changeNum: function (e) {
    var that = this;
    var id = e.currentTarget.id;
    var val = e.currentTarget.dataset.val;
    var stock = e.currentTarget.dataset.stock;
    var num = that.data.num;
    var buyNum = num[id];
    var tokenId = app.globalData.tokenId;
    if (val==1){
      if (buyNum < stock){
        buyNum = buyNum + 1
      }else{
        return false
      }
    }else{
      if (buyNum>1){
        buyNum = buyNum - 1;
      }else{
        return false
      }
    }

    http.Post("weapp/carts/changeCartGoods", { tokenId: tokenId, id: id, buyNum: buyNum}, function (res) {
      if (res.status == 1) {
        num[id] = buyNum;
        that.synNum(id,num[id]);
        that.price();
        that.setData({
          num: num,
        })
      }
    });
  },
  inputNum:function(e){
    var that = this;
    var id = e.currentTarget.id;
    var buyNum = e.detail.value;
    var stock = e.currentTarget.dataset.stock;
    var num = that.data.num;
    var tokenId = app.globalData.tokenId;
    if (buyNum < stock) {
      num[id] = parseInt(buyNum);
    }else{
      if (buyNum>0)num[id] = stock;
    }
    that.setData({
      num: num,
    })
    if (buyNum>0){
      that.synNum(id,num[id]);
      that.price();
      http.Post("weapp/carts/changeCartGoods", { tokenId: tokenId, id: id, buyNum: buyNum }, function (res) {
      })
    }
  },
  blurNum:function(e){
    var that = this;
    var id = e.currentTarget.id;
    var buyNum = (e.detail.value && e.detail.value>0) ? e.detail.value : 1;
    var num = that.data.num;
    num[id] = parseInt(buyNum);
    that.synNum(id,num[id]);
    that.setData({
      num: num,
    })
  },
  synNum:function(cartId,num){
    var that = this;
    for (var p in that.data.data) {
      for (var i in that.data.data[p]['list']) {
           if(that.data.data[p]['list'][i].cartId==cartId)that.data.data[p]['list'][i].cartNum = num;
      }
    }
  },
  //删除
  delete(e) {
    var that = this;
    var id = e.currentTarget.id;
    var tokenId = app.globalData.tokenId;
    wx.showModal({
      title: '提示',
      content: '确定删除该商品吗?',
      success: function (res) {
        if (res.confirm) {
          http.Post("weapp/carts/delCart", { tokenId: tokenId, id: id }, function (res) {
            if (res.status == 1) {
              that.getData();
            } else {
              app.prompt(res.msg);
            }
            //app.getCartNum();
            app.updateBottomNum('cart');
          });
        }
      }
    })
  },
  //结算
  settlement:function(){
    var that = this;
    var check = that.data.check;
    var isCheck = 0;
    for (var c in check) {
      for (var i in check[c]['list']) {
        if (check[c]['list'][i] == 1) isCheck = 1;
      }
    }
    if (isCheck==0){
      app.prompt('请勾选要结算的商品');
      return false;
    }
    wx.navigateTo({
      url: '../settlement/settlement'
    })
  }
})