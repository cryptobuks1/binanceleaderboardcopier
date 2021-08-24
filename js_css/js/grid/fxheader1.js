(function() {
    var flag=false;
    var tid; //table id as defined on html page
    var sheight;
    function ge$(d) {return document.getElementById(d);}
    this.scrollHeader = function() {
        if(flag) {
            return;
        }
        var fh=ge$('Scroller:fx');
        var sd=ge$(tid+':scroller');
        fh.style.left=(0-sd.scrollLeft)+'px';
    };
    function addScrollerDivs() {
        if(ge$(tid+':scroller')) {
            return;
        }
        var sd=document.createElement("div");
        var tb=ge$(tid);
        sd.style.height=sheight+"px";
        sd.style.overflow='visible';
        sd.style.overflowX='auto';
        sd.style.overflowY='auto';
        sd.style.width=tb.width;
        sd.id=tid+':scroller';
        sd.onscroll=scrollHeader;
        
        var tb2=tb.cloneNode(true);
        sd.appendChild(tb2);
        tb.parentNode.replaceChild(sd,tb);
        var sd2=document.createElement("div");
        sd2.id='Scroller:fx:OuterDiv';
        sd2.style.cssText='position:relative;width:100%;overflow:hidden;overflow-x:hidden;padding:0px;margin:0px;';
        sd2.innerHTML='<div id="Scroller:fx" style="position:relative;width:9999px;padding:0px;margin-left:0px;"><div id="Scroller:content"><font size="3" color="red">Please wait while loading the table..</font></div></div>';
        sd.parentNode.insertBefore(sd2,sd);
    }
    function fxheader() {
        if(flag) {return;}
        flag=true;
        var tbDiv=ge$(tid);
        tbDiv.rows[0].style.display='';
        var twp=tbDiv.width;
        var twi=parseInt(twp);
        if(twp.indexOf("%") > 0) {
            twi=((ge$('Scroller:fx:OuterDiv').offsetWidth * twi) / 100)-20;
            twp=twi+'px';
            tbDiv.style.width=twp;
        }
        var oc=tbDiv.rows[0].cells;
        var fh=ge$('Scroller:fx');
        var tb3=tbDiv.cloneNode(true);
        tb3.id='Scroller:content';
        tb3.style.marginTop = '0px';
        fh.replaceChild(tb3,ge$('Scroller:content'));
        ge$('Scroller:fx:OuterDiv').style.height=oc[0].offsetHeight+'px';
        tbDiv.style.marginTop = "-" + tbDiv.rows[0].offsetHeight + "px";
        scrollHeader();
        if(tbDiv.offsetHeight < sheight) {
            ge$(tid+':scroller').style.height=tbDiv.offsetHeight + 'px';
        }
        window.onresize=fxheader;
        flag=false;
    }
    this.fxheaderInit = function(_tid,_sheight) {
        tid=_tid;
        sheight=_sheight;
        flag=false;
        addScrollerDivs();
        fxheader();
    };
})();