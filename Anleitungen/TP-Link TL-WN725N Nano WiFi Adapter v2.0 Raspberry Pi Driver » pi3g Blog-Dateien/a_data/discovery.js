DISQUS.define("discovery.collections",function(){"use strict";var a=_.strip,b=DISQUS.use("discovery.models"),c=DISQUS.use("discovery.helpers"),d=Backbone.Collection.extend({url:function(a){return DISQUS.api.getURL(a)},fetch:function(a){return a=a||{},a.reset=!0,Backbone.Collection.prototype.fetch.call(this,a)},parse:function(a){return a.response}}),e=function(b){var c=b.prototype;return b.extend({url:function(){return c.url.call(this,"discovery/listTopPost.json")},parse:function(b){for(var d=c.parse.call(this,b),e=0,f=d.length;f>e;e++)d[e].plaintext=a(d[e].message);return d}})}(d),f=function(a){return a.extend({initialize:function(){this.model=b[this.modelName]}})}(d),g=function(a){var b=a.prototype;return a.extend({modelName:"RelatedThread",url:function(){return b.url.call(this,"discovery/listRelated.json")}})}(f),h=function(a){return a=a||{},a.dataType="jsonp",a.omitDisqusApiKey=!0,a.data=a.data||{},delete a.data.thread,a},i=function(a){var b=a.prototype;return a.extend({modelName:"Advertisement",url:"//tempest.services.disqus.com/listPromoted",fetch:_.compose(function(a){return _.has(a.data,"limit")&&(a.data.count=a.data.limit,delete a.data.limit),b.fetch.call(this,a)},h)})}(f),j=function(a){var b=a.prototype;return a.extend({initialize:function(a,c){b.initialize.apply(this,arguments),this.sessionStorage=c&&c.sessionStorage||window.sessionStorage},modelName:"TaboolaAdvertisement",url:"http://api.taboola.com/1.1/json/disqus/recommendations.get",getTaboolaSession:function(){var a;try{a=this.sessionStorage.getItem("taboolaSession")}catch(b){}return a||"init"},setTaboolaSession:function(a){try{this.sessionStorage.setItem("taboolaSession",a)}catch(b){c.log("Unable to store Taboola session in sessionStorage")}},fetch:_.compose(function(a){return _.extend(a.data,{"app.type":"desktop","app.apikey":"037849ccb5a799c70e319e9592c66e8b387105ff","source.type":"text","source.id":a.sourceThread.id,"source.url":a.sourceThreadUrl,"user.session":this.getTaboolaSession()}),a.jsonpCallback="rec.callback",_.has(a.data,"limit")&&(a.data["rec.count"]=a.data.limit,delete a.data.limit),b.fetch.call(this,a)},h),parse:function(a){this.setTaboolaSession(a.session);var b=79264;return _.map(a.list,function(a){return a.advertisement_id=b++,a})}})}(f),k={PostCollection:e,RelatedThreadCollection:g,AdvertisementCollection:i,TaboolaAdvertisementCollection:j};return DISQUS.testing&&(k.BaseCollection=d,k.BaseContentCollection=f),k}),DISQUS.define("discovery.helpers",function(a,b){"use strict";var c=!1,d=!1,e=function(a){c=!!a.lineTruncationEnabled,d=!!a.consoleLoggingEnabled},f=function(){return DISQUS.browser.mobile},g=function(){};a.console&&(g=function(){if(d){var b=_.toArray(arguments);b.unshift("[Discovery]"),a.console.log.apply?a.console.log.apply(a.console,b):a.console.log(b.join(" "))}});var h=function(a){return a===b?d:(d=!!a,void 0)},i=function(a){return a===b?c:(c=!!a,void 0)},j=function(b,d){function e(){return j.scrollHeight-j.offsetHeight>.2*k}function f(){i.lastChild&&!_.contains(["...","…"],i.lastChild.nodeValue)&&(l=i.appendChild(a.document.createTextNode(" "+n)),e()&&(i.removeChild(l),i.removeChild(i.lastChild),f()))}if(c){var g=DISQUS.logError||function(){};if(!b.closest("body").length)return void g("lineTruncate called on el not on DOM");if(b.text().length<1)return void g("lineTruncated called on empty el");var h=function(a){return 3!==a.nodeType};if(_.any(b.children(),h))return void g("lineTruncate called on non-flat el");var i=b[0],j=i;if("block"!==b.css("display"))for(;j.parentNode&&(j=j.parentNode,"block"!==$(j).css("display")););var k=parseFloat(b.css("font-size"),10);if(e()){d=d||{};var l,m=d.lines||1,n=d.ellipsis,o=b.text();if(o.length){var p=b.width()/k,q=parseInt(p*m,10),r=o.split(/\s/),s=0;b.empty();for(var t=0,u=r.length;u>t&&(s+=r[t].length+1,!(s>=q));t++)i.appendChild(document.createTextNode(" "+r[t]));if(e()){do l=i.removeChild(i.lastChild);while(e())}else{do l=i.appendChild(document.createTextNode(" "+r[t++]));while(!e()&&u>t);i.removeChild(l)}n&&(_.isString(n)||(n="…"),f())}}}},k=function(a){function b(a,b){return a+b}var c,d,e=_.keys(a),f=Math.floor(_.reduce(a,b,0)/2),g=e.length+1,h=f+1,i=new Array(g);for(c=0;g>c;c++)i[c]=new Array(h),i[c][0]={};for(d=1;h>d;d++)i[0][d]=!1;var j,k,l,m={};for(d=1;h>d;d++)for(c=1;g>c;c++)j=e[c-1],k=a[j],l=_.clone(i[c-1][d]),!l&&d>=k&&(l=_.clone(i[c-1][d-k]),l&&(l[j]=k,m=l)),i[c][d]=l;return[m,_.omit(a,_.keys(m))]};return{config:e,isMobile:f,log:g,allowLog:h,allowLineTruncate:i,lineTruncate:j,balancedPartition:k}}),DISQUS.define("discovery",function(){"use strict";function a(a){var b=_.object(_.map(["lineTruncationEnabled","consoleLoggingEnabled"],function(b){return b in a?[b,a[b]]:[b,e.prototype.defaults[b]]}));return d.config(b),new e(a)}var b=DISQUS.use("discovery.collections"),c=DISQUS.use("discovery.views"),d=DISQUS.use("discovery.helpers"),e=Backbone.Model.extend({defaults:{name:"promoted",inlineMeta:!0,contentPreviews:!1,promotedEnabled:!0,topPlacementEnabled:!1,redirectUrl:"//redirect.disqus.com/url",listRelatedLimit:null,listPromotedLimit:null,httpTimeout:1e4,sourceThread:null,sourceForum:null,sourceThreadUrl:null,useTaboolaAdserver:!1,help:!1,display:!1,columnEveningEnabled:!0,numColumns:2,minPerColumn:1,maxPerColumn:4,toleranceCoefficient:1.2,minWidthForColumnLayout:440,containerId:"discovery",topPlacementContainerId:"discovery-top",innerContainerName:"discovery-main",sectionNames:["col-organic","col-promoted"],collectionTagName:"ul",collectionClassName:"discovery-posts",consoleLoggingEnabled:DISQUS.debug,lineTruncationEnabled:!0,session:null,js:null,css:null},initialize:function(){var a=this;this.collections=[],a.configure(),a.once("change:display",function(){a.onComplete()}),_.bindAll(a,"getContentPreviews","validateData","showData"),a.run()},configure:function(){var a=this;a.set("innerContainerId",a.get("innerContainerName")+"-"+a.cid),a.set("sectionIds",_.map(a.get("sectionNames"),function(b){return b+"-"+a.cid}));var b=a.get("session");a.get("topPlacementEnabled")&&b.isAnonymous()&&a.set("containerId",a.get("topPlacementContainerId"))},numSections:function(){return this.collections.length||this.get("promotedEnabled")?2:1},commonClickMetadata:function(){var a=this.get("sourceThread"),b=this.get("sourceForum"),c={redirectUrl:this.get("redirectUrl"),sourceThreadId:a.id,forumId:b.pk,forum:b.id,majorVersion:this.majorVersion(),requestBin:this.get("requestBin")},d=this.get("session");return d&&d.isRegistered()&&(c.userId=d.user.id),c},augmentModels:function(a){a.invoke("set",this.commonClickMetadata())},run:function(){var a=_.bind(this.onComplete,this);this.getData().then(this.validateData).then(this.showData).otherwise(a)},generateFetchOptions:function(){var a=this.get("numColumns"),b=this.numSections();return{timeout:this.get("httpTimeout"),data:{thread:this.get("sourceThread").id,limit:a/b*this.get("maxPerColumn")},sourceThread:this.get("sourceThread")}},getDataOrganic:function(){var a=this;a.threads=new b.RelatedThreadCollection;var c=this.generateFetchOptions();c.humanFriendlyTimestamp=!0,a.has("listRelatedLimit")&&(c.data.limit=a.get("listRelatedLimit"));var d=when(a.threads.fetch(c));return a.get("contentPreviews")&&(d=d.then(a.getContentPreviews)),d},getDataPromoted:function(){var a,c=this,d=c.generateFetchOptions();c.get("useTaboolaAdserver")?(a=b.TaboolaAdvertisementCollection,c.set("listPromotedLimit",4),d.sourceThreadUrl=c.get("sourceThreadUrl")):a=b.AdvertisementCollection,c.ads=new a,c.has("listPromotedLimit")&&(d.data.limit=c.get("listPromotedLimit"));var e=when(c.ads.fetch(d)),f=e.then(function(a){var b=a.bin;return b?c.has("requestBin")?e:(c.set("requestBin",b),c.handleExperiment(b)):e});return f},getData:function(){var a=this.getDataOrganic();if(!this.get("promotedEnabled"))return a;var b=this.getDataPromoted();return when.join(a,b)},handleExperiment:function(a){return this.experimentHandlers[a]?this.experimentHandlers[a].call(this):void 0},experimentHandlers:{"embed:promoted_discovery:tempest:taboola:active":function(){return this.set("experimentVariant","taboola"),this.set("useTaboolaAdserver",!0),this.getDataPromoted()}},getContentPreviews:function(){var a=this.threads.pluck("id"),c=this.numSections(),d=this.get("numColumns"),e=this.get("minPerColumn");if(a.length<d/c*e)return when.resolve();this.previews=new b.PostCollection;var f=this.listTopPostRequest=when(this.previews.fetch({data:{thread:a},timeout:this.get("httpTimeout")}));return f.then(_.bind(this.attachPreviews,this))},attachPreviews:function(){var a=this;a.previews.each(function(b){var c=b.get("thread"),d=c.id||c;c=a.threads.get(d),c&&c.set("preview",b)})},validateCollections:function(){for(var a,b,c=this.collections,d=c.length,e=this.get("numColumns"),f=this.get("minPerColumn");d>0;)b=e/c.length*f,a=c[--d],a.length<b&&(c.splice(d,1),d=c.length);if(d=c.length,d>0)for(var g=e/d*this.get("maxPerColumn"),h=0;d>h;h++)a=c[h],a.length>g&&a.reset(a.slice(0,g))},validateData:function(){if(d.isMobile()&&this.ads&&this.ads.length>0&&this.ads.remove(this.ads.where({mobile:!1})),this.collections=_.compact([this.threads,this.ads]),this.validateCollections(),_.each(this.collections,this.augmentModels,this),0===this.collections.length)throw"Not enough data"},showData:function(){function a(a){_.extend(this,a),this.appContext=b.toJSON()}var b=this,d=document.getElementById(b.get("containerId"));if(!d)throw"No container on the DOM";var e=b.mainView=new c.MainView(new a({el:d,model:b}));e.render();var f=b.get("sectionIds"),g=b.get("collectionTagName"),h=b.get("collectionClassName");if(b.views=_.map(b.collections,function(b,d){var e=new c.BaseCollectionView(new a({collection:b,el:$("#"+f[d]+" "+g+"."+h)}));return e}),2===b.views.length&&e.$el.find("#"+b.get("innerContainerId")).addClass("doublesection"),_.invoke(b.views,"render"),b.get("columnEveningEnabled")&&e.$el.width()>b.get("minWidthForColumnLayout")){var i=new c.TwoColumn({views:b.views,fudge:this.get("toleranceCoefficient")});i.render()}else{var j=_.min(_.pluck(b.collections,"length"));_.each(b.views,function(a){for(;a.collection.length>j;)a.collection.pop()})}b.set("display",!0)},onComplete:function(a){var b=d.log;return this.onCompleteCalled?b("Error: Final reporting function called more than once"):(this.onCompleteCalled=!0,a&&b("It looks like there was a problem:",a),this.report(),void 0)},report:function(){var a=d.log,b=this.snapshot(),c=DISQUS.juggler.client("juggler");if(!c)return void a("Cannot report app state, no client found");if(a("Sending analytics data about this Discovery impression:"),a("init_discovery: ",b),c.emit("init_discovery",b),this.get("darkJester")){var e=DISQUS.juggler.client("jester",!0);e.emit("init_discovery",b)}},majorVersion:function(){return this.get("promotedEnabled")?"midway":"metadata"},snapshot:function(){var a=this.threads,b={major_version:this.majorVersion(),internal_organic:a.length,external_organic:0,promoted:0,display:this.get("display"),placement:this.get("containerId")===this.get("topPlacementContainerId")?"top":"bottom"};if(this.has("requestBin")&&(b.bin=this.get("requestBin")),this.get("promotedEnabled")){var c=this.ads;_.extend(b,{promoted:c.length,promoted_ids:DISQUS.JSON.stringify(c.pluck("advertisement_id"))})}return b}}),f={};return DISQUS.testing&&(f.DiscoveryApp=e),f.init=a,f}),DISQUS.define("discovery.models",function(){"use strict";var a=DISQUS.use("time"),b=function(a){var b=a.prototype;return a.extend({defaults:{redirectUrl:null,signedUrl:null,userId:null,sourceThreadId:null,forumId:null,forum:null,majorVersion:null,requestBin:null},redirectPayload:function(){var a={url:this.get("signedUrl"),imp:DISQUS.juggler.impId,forum_id:this.get("forumId"),forum:this.get("forum"),thread_id:this.get("sourceThreadId"),major_version:this.get("majorVersion")};return this.has("requestBin")&&(a.bin=this.get("requestBin")),this.has("userId")&&(a.user_id=this.get("userId")),a},redirectUrl:function(){var a=this.get("redirectUrl"),b=this.redirectPayload();return DISQUS.serialize(a,b)},toJSON:function(){var a=b.toJSON.call(this);return a.redirectUrl=this.redirectUrl(),a},toString:function(){return this.get("title")+" "+this.get("link")+" (id = "+this.id+")"}})}(Backbone.Model),c=function(b){var c=b.prototype;return b.extend({defaults:_.defaults({createdAgo:!1},c.defaults),initialize:function(b,c){if(c&&c.humanFriendlyTimestamp){var d=a.assureTzOffset(this.get("createdAt"));d=moment(d,a.ISO_8601),this.set("createdAgo",d.fromNow())}},redirectPayload:function(){var a=c.redirectPayload.call(this);return _.extend(a,{thread:this.id,zone:"internal_discovery"}),a},toJSON:function(){var a=c.toJSON.call(this);return a.preview&&(a.preview=a.preview.toJSON()),a},toString:function(){return"organic link: "+c.toString.call(this)}})}(b),d=function(a){var b=a.prototype;return a.extend({idAttribute:"advertisement_id",defaults:_.defaults({brand:null,headline:null,text:null,url:null,signedUrl:null,advertisement_id:null},b.defaults),parse:function(a){return a.signedUrl=a.signed_url,delete a.signed_url,a},get:function(a){return{title:this.attributes.headline,link:this.attributes.url}[a]||b.get.call(this,a)},redirectPayload:function(){var a=b.redirectPayload.call(this);return _.extend(a,{zone:"promoted_discovery",advertisement_id:this.get("advertisement_id"),brand:this.get("brand"),headline:this.get("headline")}),a},toJSON:function(){var a=b.toJSON.call(this);return a.title=a.headline,a.link=a.url,a},toString:function(){return"promoted link: "+b.toString.call(this)}})}(b),e=function(a){return a.extend({idAttribute:"advertisement_id",apiMapping:{headline:"name",signedUrl:"url",brand:"branding"},parse:function(a){return _.each(this.apiMapping,function(b,c){a[b]&&(a[c]=a[b],delete a[b])}),a}})}(d),f={RelatedThread:c,Advertisement:d,TaboolaAdvertisement:e};return DISQUS.testing&&(f.BaseContentModel=b),f}),DISQUS.define("discovery.views",function(){"use strict";var a=DISQUS.use("discovery.helpers"),b=Backbone.View.extend({initialize:function(a){a&&a.appContext&&(this.appContext=a.appContext)},getTemplateContext:function(){return this.appContext?{variant:this.appContext}:{}},template:function(a,b){return b=b||this.templateName,DISQUS.renderBlock(b,a)}}),c=function(c){var d=c.prototype;return c.extend({events:{"click [data-redirect]":"handleClick"},handleClick:function(a){this.swapHref(a.currentTarget)},swapHref:function(a){a.setAttribute("data-href",a.getAttribute("href")),a.setAttribute("href",a.getAttribute("data-redirect")),_.delay(function(){a.setAttribute("href",a.getAttribute("data-href"))},100)},templateName:"discoveryCollection",initialize:function(a){d.initialize.call(this,a),this.elementsSelector="li.discovery-post",this.$elements=this.$el.find(this.elementsSelector);var b=this.collection;b.on("remove",this.remove,this),b.on("reset",this.render,this)},truncate:function(){var b=this.$el.find(".line-truncate");_.each(b,function(b){var c=$(b);a.lineTruncate(c,{lines:c.attr("data-line-truncate"),ellipsis:!0})})},getTemplateContext:function(){var a=d.getTemplateContext.call(this);a.collection=this.collection.toJSON();var b=this.collection.at(0),c=b.has("id")?"organic-":"promoted-",e=b.idAttribute;return _.each(a.collection,function(a){a.domIdSuffix=a[e],a.domIdSuffix=c+a.domIdSuffix}),a},render:function(){var a=this.getTemplateContext();return this.$el.html(this.template(a)),this.$elements=this.$el.find(this.elementsSelector),this.truncate(),this},remove:function(a,c,d){if(0===arguments.length)return b.prototype.remove.call(this);var e=_.toArray(this.$elements),f=e.splice(d.index,1)[0];return $(f).remove(),this.$elements=$(e),this}})}(b),d=function(a,b){this.modelIds=a||[],this.$elements=$(b||[])};_.extend(d.prototype,{height:function(){var a=this;a.heights=[];var b=$(a.$elements),c=b.first().offset().top,d=function(){var a=b.last().offset();return a.top+a.height}(),e=d-c,f=0;return _.each(b,function(b){var c=$(b).height();a.heights.push(c),f+=c}),this.interstice=(e-f)/(b.length-1),e}});var e=function(){this.divideIntoColumns=function(){var a=this,b=a.subviews[0];a.left=new d,a.right=new d;var c=0;b.collection.each(function(d,e){var f=0===c++%2?"left":"right";a[f].modelIds.push(d.id),Array.prototype.push.call(a[f].$elements,b.$elements[e])})},this.removeOneFromColumn=function(a,b){var c,d=_.chain(a.modelIds).map(function(b,c){return[b,a.heights[c]]}).sortBy(function(a){return-1*a[1]}).find(function(a){return a[1]<=b}).value()[0],e=this.subviews[0].collection,f=e.models,g=e.get(d),h=f.indexOf(g),i=[],j=[],k=[j,i],l=f.length;for(c=0;l>c;c++)k[c%2].push(f[c]);var m=k[h%2];m.splice(_.indexOf(m,g),1),f=[];var n=(h+1)%2;for(c=0;l-1>c;c++)f.push(k[(c+n)%2].shift());e.reset(f)},this.balanceColumns=function(){var b=this.subviews[0],c=b.collection,d={};c.each(function(a,c){d[c]=b.$elements[c]});var e=a.balancedPartition(d);e=_.sortBy(e,"length");var f=e[1],g=e[0],h=c.models,i=new Array(h.length);_.each(f,function(a,b){i[2*b]=h[b]}),_.each(g,function(a,b){i[2*b+1]=h[b]}),c.reset(h)},this.shortenColumn=function(a,b){var c=this.subviews[0].collection;0!==c.length%2&&a===this.left?this.removeOneFromColumn(a,this.fudge*b):this.balanceColumns()}},f=function(){this.divideIntoColumns=function(){var a=this,b=a.subviews,c=b[0],e=b[1],f=c.collection.model.prototype.idAttribute;a.left=new d(c.collection.pluck(f),c.$elements);var g=e.collection.model.prototype.idAttribute;a.right=new d(e.collection.pluck(g),e.$elements)},this.shortenColumn=function(a,b){for(var c=a===this.left?this.subviews[0]:this.subviews[1],d=a===this.left?this.right:this.left,e=d,f=b/e.$elements.length,g=c.collection,h=_.chain(a.modelIds).map(function(b,c){return[b,a.heights[c]]}).sortBy(function(a){return a[1]}).value(),i=[],j=0,k=b,l=f;h.length;){var m=h.pop(),n=m[0],o=m[1],p=o+a.interstice;if(j+p>b&&(e=a),k=Math.abs(b-(j+p)),l=k/e.$elements.length,!(l>=f)){f=l;var q=a.modelIds.indexOf(n);a.modelIds.splice(q,1),Array.prototype.splice.call(a.$elements,q,1),j+=p,i.push(n)}}g.remove(i)}},g=function(a){this.fudge=a.fudge,this.subviews=a.views.slice(0,2),1===this.subviews.length?e.call(this):f.call(this)};_.extend(g.prototype,{ascendingByHeight:function(){var a=this.left,b=this.right,c=[[a,a.height()],[b,b.height()]];return _.sortBy(c,function(a){return a[1]})},evenColumns:function(a){var b=this.ascendingByHeight(),c=b[0][0],d=b[0][1],e=b[1][0],f=b[1][1];if(d!==f){var g=f-d,h=this.fudge*g,i=_.find(e.heights,function(a){return a+e.interstice<h});return!a&&i?(this.shortenColumn(e,g),this.divideIntoColumns(),this.evenColumns("do not recurse again")):(this.increaseMargins(c,g),void 0)}},increaseMargins:function(a,b){var c=a.$elements.length,d=b/c;_.each(a.$elements,function(a){var b=$(a),c=parseInt(b.css("margin-bottom"),10),e=c+d;b.css("margin-bottom",e+"px")});var e=a===this.left?this.right:this.left,f=a===this.right?"left":"right";e.$elements.css("clear",f)},render:function(){return this.divideIntoColumns(),this.evenColumns(),this}});var h=function(a){var b=a.prototype;return a.extend({templateName:"discoveryMain",events:{"click [data-action=discovery-help]":function(a){a.preventDefault(),this.model.set("help",!0)},"click [data-action=discovery-help-close]":function(a){a.preventDefault(),this.model.set("help",!1)}},toggleHelp:function(a){var b=this;b.$el.find("#discovery-note").toggle(),a.trigger("resize")},initialize:function(a){b.initialize.call(this,a),this.model.on("change:display",this.show,this),this.model.on("change:help",this.toggleHelp,this),this.$el.css({position:"absolute",visibility:"hidden",display:"block"})},createSections:function(){var a=this.model,b=a.get("sectionNames"),c=a.get("sectionIds");return _.map(a.collections,function(d,e){var f;return d===a.threads?f="organic":d===a.ads&&(f="promoted"),{id:c[e],className:b[e],type:f}})},render:function(){var a=this.model,b=this.createSections();this.$el.html(this.template({id:a.get("innerContainerId"),sections:b,forum:a.get("sourceForum"),session:a.get("session").toJSON()}))},show:function(a){a.get("display")&&(this.$el.css({position:"static",visibility:"visible"}),a.trigger("resize"))}})}(b);return{BaseCollectionView:c,TwoColumn:g,MainView:h}}),this.Handlebars=this.Handlebars||{},this.Handlebars.discovery=this.Handlebars.discovery||{},this.Handlebars.discovery.templates=Handlebars.template(function(a,b,c,d,e){function f(a,b){var d,e="";return e+="\n    ",d=c.each.call(a,a.collection,{hash:{},inverse:I.noop,fn:I.programWithDepth(g,b,a),data:b}),(d||0===d)&&(e+=d),e+="\n"}function g(a,b,e){var f,g,i,j="";return j+='\n        <li class="discovery-post" id="discovery-link-',(f=c.domIdSuffix)?f=f.call(a,{hash:{},data:b}):(f=a.domIdSuffix,f=typeof f===J?f.apply(a):f),j+=K(f)+'">\n            <header class="discovery-post-header">\n                <h3 title="',(f=c.title)?f=f.call(a,{hash:{},data:b}):(f=a.title,f=typeof f===J?f.apply(a):f),j+=K(f)+'">\n                    <a ',f=I.invokePartial(d.linkAttributes,"linkAttributes",a,c,d,b),(f||0===f)&&(j+=f),j+=' data-role="discovery-thread-title" class="title publisher-anchor-color line-truncate" data-line-truncate="2">\n                        ',i={hash:{},data:b},j+=K((f=c.html,f?f.call(a,a.title,i):L.call(a,"html",a.title,i)))+"\n                    </a>\n\n                    ",g=c["if"].call(a,(f=e.variant,null==f||f===!1?f:f.inlineMeta),{hash:{},inverse:I.noop,fn:I.program(3,h,b),data:b}),(g||0===g)&&(j+=g),j+="\n\n                </h3>\n\n                ",g=c.unless.call(a,(f=e.variant,null==f||f===!1?f:f.inlineMeta),{hash:{},inverse:I.noop,fn:I.program(11,m,b),data:b}),(g||0===g)&&(j+=g),j+="\n\n            </header>\n\n            ",g=c["if"].call(a,(f=e.variant,(null==f||f===!1?f:f.contentPreviews)&&a.preview),{hash:{},inverse:I.noop,fn:I.program(16,p,b),data:b}),(g||0===g)&&(j+=g),j+="\n\n        </li>\n    "}function h(a,b){var d,e="";return e+="\n                        ",d=c["if"].call(a,a.posts>0,{hash:{},inverse:I.program(6,j,b),fn:I.program(4,i,b),data:b}),(d||0===d)&&(e+=d),e+="\n                        ",d=c["if"].call(a,a.brand,{hash:{},inverse:I.noop,fn:I.program(9,l,b),data:b}),(d||0===d)&&(e+=d),e+="\n                    "}function i(a,b){var e,f="";return f+='\n                            <span class="inline-meta">\n                                ',e=I.invokePartial(d.discoveryPostCount,"discoveryPostCount",a,c,d,b),(e||0===e)&&(f+=e),f+="\n                            </span>\n                        "}function j(a,b){var d,e="";return e+="\n                            ",d=c["if"].call(a,a.createdAgo,{hash:{},inverse:I.noop,fn:I.program(7,k,b),data:b}),(d||0===d)&&(e+=d),e+="\n                        "}function k(a,b){var d,e="";return e+='\n                                <span class="inline-meta">',(d=c.createdAgo)?d=d.call(a,{hash:{},data:b}):(d=a.createdAgo,d=typeof d===J?d.apply(a):d),e+=K(d)+"</span>\n                            "}function l(a,b){var d,e="";return e+='\n                            <span class="inline-meta">\n                                ',(d=c.brand)?d=d.call(a,{hash:{},data:b}):(d=a.brand,d=typeof d===J?d.apply(a):d),e+=K(d)+"\n                            </span>\n                        "}function m(a,b){var d,e="";return e+='\n                    <ul class="meta">\n                        ',d=c["if"].call(a,a.posts>0,{hash:{},inverse:I.noop,fn:I.program(12,n,b),data:b}),(d||0===d)&&(e+=d),e+="\n                        ",d=c["if"].call(a,a.createdAgo,{hash:{},inverse:I.noop,fn:I.program(14,o,b),data:b}),(d||0===d)&&(e+=d),e+="\n                    </ul>\n                "}function n(a,b){var e,f="";return f+='\n                            <li class="comments">\n                                ',e=I.invokePartial(d.discoveryPostCount,"discoveryPostCount",a,c,d,b),(e||0===e)&&(f+=e),f+="\n                            </li>\n                        "}function o(a,b){var d,e="";return e+='\n                            <li class="time">',(d=c.createdAgo)?d=d.call(a,{hash:{},data:b}):(d=a.createdAgo,d=typeof d===J?d.apply(a):d),e+=K(d)+"</li>\n                        "}function p(a,b){var e,f="";return f+="\n                ",e=I.invokePartial(d.discoveryContentPreview,"discoveryContentPreview",a,c,d,b),(e||0===e)&&(f+=e),f+="\n            "}function q(a,b){var d,e="";return e+='\n    href="',(d=c.redirectUrl)?d=d.call(a,{hash:{},data:b}):(d=a.redirectUrl,d=typeof d===J?d.apply(a):d),e+=K(d)+'" ',d=c["if"].call(a,a.brand,{hash:{},inverse:I.noop,fn:I.program(19,r,b),data:b}),(d||0===d)&&(e+=d),e+="\n"}function r(){return'target="_blank" rel="nofollow norewrite"'}function s(a,b){var e,f,g="";return g+="\n    <a ",e=I.invokePartial(d.linkAttributes,"linkAttributes",a,c,d,b),(e||0===e)&&(g+=e),g+=' class="top-comment" data-role="discovery-top-comment">\n        <img src="'+K((e=a.preview,e=null==e||e===!1?e:e.author,e=null==e||e===!1?e:e.avatar,e=null==e||e===!1?e:e.cache,typeof e===J?e.apply(a):e))+'" alt="',f={hash:{},data:b},g+=K((e=c.t,e?e.call(a,gettext("Avatar"),f):L.call(a,"t",gettext("Avatar"),f)))+'" data-role="discovery-avatar">\n        <p><span class="user" data-role="discovery-top-comment-author">'+K((e=a.preview,e=null==e||e===!1?e:e.author,e=null==e||e===!1?e:e.name,typeof e===J?e.apply(a):e))+'</span> &#8212; <span data-role="discovery-top-comment-snippet" class="line-truncate" data-line-truncate="3">'+K((e=a.preview,e=null==e||e===!1?e:e.plaintext,typeof e===J?e.apply(a):e))+"</span></p>\n    </a>\n"}function t(a,b){var d,e="";return e+="\n    ",d=c["if"].call(a,1===a.posts,{hash:{},inverse:I.program(26,v,b),fn:I.program(24,u,b),data:b}),(d||0===d)&&(e+=d),e+="\n"}function u(a,b){var d,e,f="";return f+="\n        ",e={hash:{},data:b},f+=K((d=c.t,d?d.call(a,gettext("1 comment"),e):L.call(a,"t",gettext("1 comment"),e)))+"\n    "}function v(a,b){var d,e,f="";return f+="\n        ",e={hash:{numPosts:a.posts},data:b},f+=K((d=c.t,d?d.call(a,gettext("%(numPosts)s comments"),e):L.call(a,"t",gettext("%(numPosts)s comments"),e)))+"\n    "}function w(a,b,d){var e,f,g,h="";return h+='\n<div id="',(e=c.id)?e=e.call(a,{hash:{},data:b}):(e=a.id,e=typeof e===J?e.apply(a):e),h+=K(e)+'" class="discovery-main">\n    <div id="discovery-note" style="display:none;">\n        <div class="alert">\n        <a href="#" class="close" data-action="discovery-help-close" title="',g={hash:{},data:b},h+=K((e=c.t,e?e.call(a,gettext("Close this box"),g):L.call(a,"t",gettext("Close this box"),g)))+'">×</a>\n        ',g={hash:{learnMore:{partial:"learnMore",context:d,executePartial:!0},feedback:{partial:"feedback",context:d,executePartial:!0}},data:b},h+=K((e=c.t,e?e.call(a,gettext("Disqus helps you find new and interesting content, discussions and products. Some sponsors and ecommerce sites may pay us for these recommendations and links. %(learnMore)s or %(feedback)s."),g):L.call(a,"t",gettext("Disqus helps you find new and interesting content, discussions and products. Some sponsors and ecommerce sites may pay us for these recommendations and links. %(learnMore)s or %(feedback)s."),g)))+"\n        </div>\n    </div>\n\n    ",f=c.each.call(a,a.sections,{hash:{},inverse:I.noop,fn:I.programWithDepth(x,b,a),data:b}),(f||0===f)&&(h+=f),h+="\n\n</div>\n"}function x(a,b,d){var e,f="";return f+='\n        <section id="',(e=c.id)?e=e.call(a,{hash:{},data:b}):(e=a.id,e=typeof e===J?e.apply(a):e),f+=K(e)+'" class="',(e=c.className)?e=e.call(a,{hash:{},data:b}):(e=a.className,e=typeof e===J?e.apply(a):e),f+=K(e)+'">\n            <header class="discovery-col-header">\n\n                ',e=c["if"].call(a,b.index===b.length-1,{hash:{},inverse:I.noop,fn:I.program(30,y,b),data:b}),(e||0===e)&&(f+=e),f+="\n\n                ",e=c["if"].call(a,"organic"===a.type,{hash:{},inverse:I.noop,fn:I.programWithDepth(z,b,d),data:b}),(e||0===e)&&(f+=e),f+="\n                ",e=c["if"].call(a,"promoted"===a.type,{hash:{},inverse:I.noop,fn:I.program(34,A,b),data:b}),(e||0===e)&&(f+=e),f+='\n\n            </header>\n            <ul class="discovery-posts">\n            </ul>\n        </section>\n    '}function y(a,b){var d,e,f="";return f+='\n                    \n                    <div class="discovery-options">\n                        <span class="publisher-anchor-color"><a href="#" class="discovery-help" data-action="discovery-help">',e={hash:{},data:b},f+=K((d=c.t,d?d.call(a,gettext("What's this?"),e):L.call(a,"t",gettext("What's this?"),e)))+"</a></span>\n                    </div>\n                "}function z(a,b,d){var e,f,g="";return g+="\n                    <h2>",f={hash:{forumName:{partial:"forumName",context:d.forum,executePartial:!0}},data:b},g+=K((e=c.t,e?e.call(a,gettext("Also on %(forumName)s"),f):L.call(a,"t",gettext("Also on %(forumName)s"),f)))+"</h2>\n                "}function A(a,b){var d,e,f="";return f+="\n                    <h2>",e={hash:{},data:b},f+=K((d=c.t,d?d.call(a,gettext("Around The Web"),e):L.call(a,"t",gettext("Around The Web"),e)))+"</h2>\n                "}function B(a,b){var d,e,f="";return f+='\n    <a href="http://help.disqus.com/customer/portal/articles/666278-introducing-promoted-discovery-and-f-a-q-"\n        target="_blank">',e={hash:{},data:b},f+=K((d=c.t,d?d.call(a,gettext("Learn more"),e):L.call(a,"t",gettext("Learn more"),e)))+"</a>\n"}function C(a,b){var d,e,f="";return f+='\n    <a href="https://www.surveymonkey.com/s/GHK872T" target="_blank">\n        ',e={hash:{},data:b},f+=K((d=c.t,d?d.call(a,gettext("give us feedback"),e):L.call(a,"t",gettext("give us feedback"),e)))+"</a>\n"}function D(a,b){var d,e="";return e+="\n    <strong>",(d=c.name)?d=d.call(a,{hash:{},data:b}):(d=a.name,d=typeof d===J?d.apply(a):d),e+=K(d)+"</strong>\n"}this.compilerInfo=[2,">= 1.0.0-rc.3"],c=c||a.helpers,d=d||a.partials,e=e||{};var E,F,G,H="",I=this,J="function",K=this.escapeExpression,L=c.helperMissing;return G={hash:{},inverse:I.noop,fn:I.program(1,f,e),data:e},E=c.partial,F=E?E.call(b,"discoveryCollection",G):L.call(b,"partial","discoveryCollection",G),(F||0===F)&&(H+=F),H+="\n\n",G={hash:{},inverse:I.noop,fn:I.program(18,q,e),data:e},E=c.partial,F=E?E.call(b,"linkAttributes",G):L.call(b,"partial","linkAttributes",G),(F||0===F)&&(H+=F),H+="\n\n",G={hash:{},inverse:I.noop,fn:I.program(21,s,e),data:e},E=c.partial,F=E?E.call(b,"discoveryContentPreview",G):L.call(b,"partial","discoveryContentPreview",G),(F||0===F)&&(H+=F),H+="\n\n",G={hash:{},inverse:I.noop,fn:I.program(23,t,e),data:e},E=c.partial,F=E?E.call(b,"discoveryPostCount",G):L.call(b,"partial","discoveryPostCount",G),(F||0===F)&&(H+=F),H+="\n\n",G={hash:{},inverse:I.noop,fn:I.programWithDepth(w,e,b),data:e},E=c.partial,F=E?E.call(b,"discoveryMain",G):L.call(b,"partial","discoveryMain",G),(F||0===F)&&(H+=F),H+="\n\n",G={hash:{},inverse:I.noop,fn:I.program(36,B,e),data:e},E=c.partial,F=E?E.call(b,"learnMore",G):L.call(b,"partial","learnMore",G),(F||0===F)&&(H+=F),H+="\n\n",G={hash:{},inverse:I.noop,fn:I.program(38,C,e),data:e},E=c.partial,F=E?E.call(b,"feedback",G):L.call(b,"partial","feedback",G),(F||0===F)&&(H+=F),H+="\n\n",G={hash:{},inverse:I.noop,fn:I.program(40,D,e),data:e},E=c.partial,F=E?E.call(b,"forumName",G):L.call(b,"partial","forumName",G),(F||0===F)&&(H+=F),H+="\n"});