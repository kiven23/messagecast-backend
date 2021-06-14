 <html>
 <head>
 <title>
 PDF EXPORT
 </title>
 </head>
 <body>
<div class="col"><label>Name: {{$examineer['name']}}  </label><br /><label>Exam-Type: {{$examineer['get_module_type']['exam_name']}}</label><br /><label>Position: {{$examineer->position}}</label><br /><label>Branch: {{$examineer->branch}}</label></div><br /><label style="color: teal">SCORE: <strong>{{$score}}</strong></label></div>
 
<!-- <img src="{{ public_path('check.png') }}"> -->
<div class="col">
<div class="row">
<div><hr class="q-separator q-separator q-separator--horizontal q-separator--horizontal-inset" style="margin-bottom: 8px; margin-top: 8px;" role="separator" aria-orientation="horizontal" />
<div class="q-pa-md">
<div class="row">
<div class="col" style="width: 700px;">
<div class="q-list">
<div class="q-item q-item-type row no-wrap">
<div class="q-item__section column q-item__section--main justify-center">
<div class="q-item__section column q-item__section--main justify-center">
@foreach($examineer['get_examineer_test'] as $index => $data)
 
<div class="q-item__label q-item__label--overline text-overline">Question #{{$index + 1}}  </div>
<hr class="q-separator q-separator q-separator--horizontal q-separator--horizontal-inset" style="margin-bottom: 8px; margin-top: 8px;" role="separator" aria-orientation="horizontal" /></div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="q-item__label" style="color: {{$data->get_exam_qna['answer_key'] == $data->get_exam_ans['id']? 'green':'red'}}"><strong>{{$index +1}}.) {{$data->get_exam_qna['name']}}</strong></div>
 
<div>
<div class="q-radio cursor-pointer no-outline row inline no-wrap items-center q-radio--dense" style="margin-bottom: -20px;" tabindex="0" role="radio" aria-label="b.Office Equipment, Furniture and Fixtures &ndash; Branch" aria-checked="false">
<div class="q-radio__inner relative-position q-radio__inner--falsy">&nbsp;</div>
</div>
</div>
<br>
@foreach($data->get_exam_qna->get_exams as $d)
<div class="q-radio cursor-pointer no-outline row inline no-wrap items-center q-radio--dense" style="margin-bottom: -20px;  margin-left: 30px;" tabindex="0" role="radio" aria-label="b.Office Equipment, Furniture and Fixtures &ndash; Branch" aria-checked="false">
<div class="q-radio__label q-anchor--skip" style="color: {{$d['id'] == $data->get_exam_ans['id']? 'teal':''}}">{{$d['name']}}</div>
</div>
<div class="q-chip row inline no-wrap items-center text-weight-bolder bg-white text-red q-chip--colored q-chip--dense q-chip--square" style="font-size: 10px; width: 20px;">&nbsp;</div>
<div>
<div class="q-chip row inline no-wrap items-center text-weight-bolder bg-white text-red q-chip--colored q-chip--dense q-chip--square" style="font-size: 10px; width: 20px;">
<div class="q-chip__content col row no-wrap items-center q-anchor--skip">&nbsp;</div>
</div>
</div>
@endforeach

 
<div class="q-item q-item-type row no-wrap">
<div class="q-item__section column q-item__section--main justify-center">
<div class="q-item__section column q-item__section--main justify-center">
<div class="q-item__label q-item__label--overline text-overline">&nbsp;</div>
</div>
</div>
</div>
</div>
</div>
</div>
 
 
 
@endforeach
</body>
</html>