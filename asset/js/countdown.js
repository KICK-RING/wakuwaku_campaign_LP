function CountdownTimer(elm,tl,mes){
 this.initialize.apply(this,arguments);
}
CountdownTimer.prototype={
 initialize:function(elm,tl,mes) {
  this.elem = document.getElementById(elm);
  this.tl = tl;
  this.mes = mes;
 },countDown:function(){
  var timer='';
  var today=new Date();
  var day=Math.floor((this.tl-today)/(24*60*60*1000));
  var hou=Math.floor(((this.tl-today)%(24*60*60*1000))/(60*60*1000));
  var min=Math.floor(((this.tl-today)%(24*60*60*1000))/(60*1000))%60;
  var sec=Math.floor(((this.tl-today)%(24*60*60*1000))/1000)%60%60;
  var mil=Math.floor(((this.tl-today)%(24*60*60*1000))/10)%100;
  var me=this;

  if( ( this.tl - today ) > 0 ){
   timer += '<span class="p-teaser-timer--day">'+day+'</span><span class="p-teaser-timer--day__note">DAYS</span><br><span class="p-teaser-timer--date">'+this.addZero(hou)+':'+this.addZero(min)+':'+this.addZero(sec)+'.'+this.addZero(mil)+'</span>';
   this.elem.innerHTML = timer;
   tid = setTimeout( function(){me.countDown();},10 );
  }else{
   this.elem.innerHTML = this.mes;
   return;
  }
 },addZero:function(num){ return ('0'+num).slice(-2); }
}
function countdown(){
 var tl = new Date('2020/08/21 19:00:00');
 //この上の部分で終了時間を設定するYO！
 var timer = new CountdownTimer('countdown',tl,'00DAYS00:00:00.00');
 //この上の文は終了した後に表示する文字!
 timer.countDown();
}
window.onload=function(){
 countdown();
}
