@extends('layouts.app')

@section('style_link')
<style>
    .checkbox-inline label{
    margin-left:10px;
    }
</style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 mb-5" id="newsletter_id">
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- newsletter2go Integration-->
    <script id="n2g_script">
        !function(e,t,n,c,r,a,i){e.Newsletter2GoTrackingObject=r,e[r]=e[r]||function(){(e[r].q=e[r].q||[]).push(arguments)},e[r].l=1*new Date,a=t.createElement(n),i=t.getElementsByTagName(n)[0],a.async=1,a.src=c,i.parentNode.insertBefore(a,i)}(window,document,"script","https://static.newsletter2go.com/utils.js","n2g");
            var config = {
                "container": {
                    "type": "div",
                    "class": "",
                    "style": ""
                },
                "row": {
                    "type": "div",
                    "class": "",
                    "style": "margin-top: 15px;"
                },
                "columnLeft": {
                    "type": "div",
                    "class": "",
                    "style": ""
                },
                "columnRight": {
                    "type": "div",
                    "class": "",
                    "style": ""
                },
                "label": {
                    "type": "label",
                    "class": "",
                    "style": ""
                },
                "input": {
                    "class": "form-control",
                    "style": "padding: 5px 10px; border-radius: 2px; border: 1px solid #d8dee4;"
                },
                "textarea": {
                    "class": "form-control",
                    "style": "padding: 5px 10px; border-radius: 2px; border: 1px solid #d8dee4;"
                }
            };
        /*var config = {"container": {"type": "div","class": "","style": ""},"row": {"type": "div","class": "","style": "margin-top: 15px;"},"columnLeft": {"type": "div","class": "","style": ""},"columnRight": {"type": "div","class": "","style": ""},"label": {"type": "label","class": "","style": ""}};*/
        n2g('create', '8a29sbmr-m889ez4j-wz2');
        n2g('subscribe:createForm', config, 'newsletter_id');

        // On newsletter2go form creation add prefilled email
        var email_set = false;
        $("body").on('DOMSubtreeModified', "#newsletter_id", function() {
            /*console.log($("#newsletter_id img:first").length);
            console.log($("input[type=email]").val());*/
            if(email_set==false && $("#newsletter_id img:first").length>0 && $("#newsletter_id img:first").css('display') == 'none') {
                console.log('modified');
                var $emailElem = $("input[type=email]");
                if($emailElem.val()=='') {
                    $("input[type=email]").val('{{$email}}').change();
                    email_set = true;
                }
            }
        });
        
    </script>
@endsection