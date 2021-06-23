var http = require('../../../../../utils/request.js');
var util = require('../../../../../utils/util.js');
var parse = require('../../../../../pages/common/parse/parse.js');
var app = getApp();
var timer = null;
Page({
    data: {
        resourceDomain: app.globalData.resourceDomain,
        domain: app.globalData.domain,//域名
        data: '',
        animationData: false,
        parameterStatus:false,
        showModalStatus: false,
        backStatus: true,
        articelStatus:false,
        articelBackStatus:false,
        nextPrice: 0,
    },
    onLoad: function (options) {
        // options 为上一个页面传递过来的参数
        this.setData({ auctionId: options.id });
    },
    onShow: function (options) {
        this.getData();
    },
    getData: function () {
        var that = this;
        clearInterval(that.data.timer);

        wx.showLoading({ title: '加载中' });
        let id = this.data.auctionId;
        var tokenId = app.globalData.tokenId;
        http.Get("addon/auction-goods-weDetail", { tokenId: tokenId, id }, function (res) {
            wx.hideLoading();
            if (res.status == 1) {
                let _data = res.data;
                wx.setNavigationBarTitle({ title: _data.goodsName });

                parse.wxParse('auctionDesc', 'html', _data.auctionDesc, that, 5);


                that.setData({
                    data: _data,
                    auctionId: _data.auctionId,
                    currPrice: _data.currPrice,
                    fareInc: _data.fareInc,
                    auctionNum: _data.auctionNum,
                    visitNum: _data.visitNum,
                    auctionNum: _data.auctionNum,
                    cautionMoney: _data.cautionMoney,
                });
                let timer;
                if(_data.status!=-1){
                    if (_data.status == '0') {
                        let startTime = new Date(Date.parse(_data.startTime.replace(/-/g, "/")));
                        timer = that.countDown(startTime);
                    } else {
                        let endTime = new Date(Date.parse(_data.endTime.replace(/-/g, "/")));
                        timer = that.countDown(endTime);
                    }
                    that.setData({ timer: timer });
                }

            } else {
                wx.showToast({
                    title: res.msg,
                    success: function () {
                        wx.navigateBack();
                    }
                })
            }
        });
    },
    countDown: function (endTime) {
        var that = this;
        var opts = {
            endTime: endTime,
            callback: function (data) {
                if (data.last > 0) {
                    that.setData({ ...data });
                } else {
                    that.getData();
                }
            }
        };
        return util.countDown(opts);
    },

    // 出价记录
    auctionRecord:function(){
        const that = this;
        const id = that.data.auctionId;
        const tokenId = app.globalData.tokenId;
        let url = 'addon/auction-weapps-getAuctionRecord';
        http.Get(url, { tokenId: tokenId, id }, function (res) {
            if (res.status == 1) {
                that.setData({
                    recordData:res.data.data
                })
            }
            that.parameterPopup('open');
        })
    },

    // 点击查看拍卖须知
    articel:function(e){
        var statu = e.currentTarget.dataset.statu;
        this.articelPopup(statu)
    },
    // 出价记录
    articelPopup: function (statu) {
        /* 动画部分 */
        var animation = wx.createAnimation({
          duration: 300,  //动画时长  
          timingFunction: "linear", //线性  
          delay: 0  //0则不延迟  
        });
        animation.translateX(600).step();
        this.setData({
          parameterData: animation.export()
        })
        setTimeout(function () {
          animation.translateX(0).step()
          this.setData({
            parameterData: animation.export()
          })
          //关闭抽屉  
          if (statu == "close") {
            this.setData({
              articelStatus: false,
              articelBackStatus: true
            });
          }
        }.bind(this), 200)
        // 显示抽屉  
        if (statu == "open") {
          this.setData({
            articelStatus: true,
            articelBackStatus: false
          });
        }
      },

    /*跳转到店铺 */
    store: function (e){
        var shopId = e.currentTarget.dataset.shopid;
        if (shopId == 1) {
        wx.navigateTo({
            url: '../../../../../pages/shop-self/shop-self'
        })
        } else {
        wx.navigateTo({
            url: '../../../../../pages/shop-home/shop-home?shopId=' + shopId
        })
        }
    },

    // 点击查看出价记录
    parameter:function(e){
        var statu = e.currentTarget.dataset.statu;
        this.parameterPopup(statu)
    },
    // 出价记录
    parameterPopup: function (statu) {
        /* 动画部分 */
        var animation = wx.createAnimation({
          duration: 300,  //动画时长  
          timingFunction: "linear", //线性  
          delay: 0  //0则不延迟  
        });
        animation.translateX(600).step();
        this.setData({
          parameterData: animation.export()
        })
        setTimeout(function () {
          animation.translateX(0).step()
          this.setData({
            parameterData: animation.export()
          })
          //关闭抽屉  
          if (statu == "close") {
            this.setData({
              parameterStatus: false,
              backStatus: true
            });
          }
        }.bind(this), 200)
        // 显示抽屉  
        if (statu == "open") {
          this.setData({
            parameterStatus: true,
            backStatus: false
          });
        }
      },


    /*加入购物车 */
    powerDrawer: function (e) {
        var statu = e.currentTarget.dataset.statu;
        this.setData({ nextPrice: this.data.currPrice });
        this.util(statu);
    },
    util: function (statu, types) {
        /* 动画部分 */
        // 第1步：创建动画实例   
        var animation = wx.createAnimation({
            duration: 300,  //动画时长  
            timingFunction: "linear", //线性  
            delay: 0  //0则不延迟  
        });

        // 第2步：这个动画实例赋给当前的动画实例  
        this.animation = animation;

        // 第3步：执行第一组动画：Y轴偏移260px后(盒子高度是240px)，停  
        animation.translateY(400).step();

        // 第4步：导出动画对象赋给数据对象储存  
        this.setData({
            animationData: animation.export()
        })

        // 第5步：设置定时器到指定时候后，执行第二组动画  
        setTimeout(function () {
            // 执行第二组动画：Y轴不偏移，停  
            animation.translateY(0).step()
            // 给数据对象储存的第一组动画，更替为执行完第二组动画的动画对象  
            this.setData({
                animationData: animation.export()
            })

            //关闭抽屉  
            if (statu == "close") {
                this.setData({
                    showModalStatus: false,
                    backStatus: true
                });
            }
        }.bind(this), 200)
        // 显示抽屉  
        if (statu == "open") {
            // this.handleData();
            this.setData({
                showModalStatus: true,
                backStatus: false
            });
        }
    },
    /*数量变化 */
    changeNum(e) {
        var that = this;
        var mode = e.currentTarget.dataset.mode;
        var min = e.currentTarget.dataset.min;
        let fareInc = this.data.fareInc
        var num = that.data.nextPrice;
        if (mode == 'plus') {
            num = Number(num) + Number(fareInc);
        } else if (mode == 'reduce' && num > min) {
            num = Number(num) - Number(fareInc);
        } else {
            return false;
        }
        that.setData({
            nextPrice: num
        });
    },
    // 支付保证金
    doPay: function () {
        wx.navigateTo({
            url: `/addons/package/pages/auction/payment/payment?auctionId=${this.data.auctionId}&payObj=bao`
        })
    },
    //加入购物车
    commit: function (e) {
        var that = this;
        var tokenId = app.globalData.tokenId;
        let auctionId = this.data.auctionId;
        let payPrice = this.data.nextPrice;
        let url = 'addon/auction-Weapps-addAcution';
        let postData = {
            id: auctionId,
            payPrice: payPrice,
            tokenId: tokenId,
        }
        http.Post(url, postData, function (res) {
            that.util('close');
            app.prompt(res.msg);
            that.doneAuction();
        });
    },
    // 刷新竞拍情况
    doneAuction: function () {
        let that = this;
        var tokenId = app.globalData.tokenId;
        let id = this.data.auctionId;
        let url = 'addon/auction-Weapps-getAuctionInfo?id=' + id+'&tokenId=' + tokenId;
        http.Get(url, {}, function (responData) {
            if (responData.status == 1) {
                let data = responData.data;
                // 更新浏览数、当前竞拍价、出价人数
                that.setData({
                    currPrice: data.currPrice,
                    auctionNum: data.auctionNum,
                    visitNum: data.visitNum,
                });
            }
        })
    }
});
