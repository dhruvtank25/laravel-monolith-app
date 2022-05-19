@extends('layouts.app')

@section('style_link')
    <link rel="stylesheet" href="{{ asset('frontend/css/dropzone.css') }}">
@endsection

@section('content')

    <!-- Modal -->
    <div class="modal fade coach_msg_modal" id="coach_message_modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mb-3" id="exampleModalLongTitle">Neuer Nachricht verfassen</h5>
            </div>
            <div class="modal-body coach_msg_body">
                <form method="post" action="{{url('coach/new-conversation')}}" id="messageFrm">
                    @csrf
                    <input type="hidden" name="conv_id" id="conv_id" value="0" required>
                    <input type="hidden" name="attachments" id="attachment" value="" required>
                    <input type="text" class="form-control mb-2" id="subject" name="subject" placeholder="Betreff" required>
                    <textarea class="form-control mb-3" id="message" name="message" placeholder="Text eingeben..." required></textarea>
                    {{-- <div class="pop_file_upload">
                        <div class="file-upload">
                            <button type="button" class="btn modal_file_upload">Anhang hinzufügen</button>
                            <input type="file" class="upload" id="myFile" name="filename2" />
                        </div>
                    </div> --}}
                    <div id="dropzone" class="dropzone message_upload_wrapper">
                        <div class="row">
                            <div class="dz-message">
                                <div class="pop_file_upload">
                                    <div class="file-upload">
                                        <button type="button" class="btn modal_file_upload">Anhang hinzufügen</button>
                                    </div>
                                </div>
                            </div>
                            <div class="dropzone-previews">
                            </div>
                        </div>
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                    </div>
                    <button type="button" class="btn stop_modal_btn mt-3" data-dismiss="modal">Verwerfen</button>
                    <button type="submit" class="btn choose_modal_btn mt-3">Absenden</button>
                </form>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> -->
        </div>
      </div>
    </div>
    <!-- Modal end-->

    <!-- coach tab data section start -->
    <div class="container-fluid ch_overview_container">
        <div class="container">
            <div class="row">
                <h5 class="col-12 ch_tabdata_head">Mitteilungen</h5>

                <div class="col-12">
                    @include('layouts.section.notifications')
                </div>

                <!-- coach message section -->
                <div class="message_coach_wrapper">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 accordion coach_msg_accord" id="accordionExample">
                                @foreach($threads as $thread)
                                    @php 
                                        $messages = $thread->messages;
                                        $unread   = $messages->where('user_id', '!=', $user_id)->where('is_seen', 0)->count();
                                    @endphp
                                    <div class="card {{$unread>0?'unreadmsg':''}}">
                                        <div class="card-header" id="heading_{{$thread->id}}">
                                          <h2 class="mb-0">
                                            <button class="btn btn-link btn-accordion collapsed" type="button" data-toggle="collapse" data-target="#thread_{{$thread->id}}" aria-expanded="false" aria-controls="#thread_{{$thread->id}}">
                                              {{$thread->subject}} <p class="float-right create_thread_date">{{$thread->created_at->format('d.M Y')}}</p>
                                            </button>
                                          </h2>
                                        </div>
                                        <div id="thread_{{$thread->id}}" data-thread-id="{{$thread->id}}" class="collapse" aria-labelledby="heading_{{$thread->id}}" data-parent="#accordionExample">
                                            <div class="card-body ">
                                                @foreach ($messages as $message)
                                                    @php
                                                        if(!$message->sender)
                                                            continue;
                                                    @endphp
                                                    <div class="msg_thread_wrapper">
                                                        <div class="coach_msg_content ch_msg_pro_pic">
                                                            <img src="{{ FileUploadHelper::getDocPath($message->sender->avatar, 'avatar') }}" alt="x"/>
                                                        </div>
                                                        <div class="coach_msg_content ch_msg_para">
                                                            <h6 class="mb-1">{{$message->sender->first_name}} von himmlischberaten.de</h6>
                                                            <p class="ch_date_msg mb-4">{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->format('d.M Y')}}</p>
                                                            <p class="mb-3">{{$message->message}}</p>
                                                            @if($message->attachments)
                                                                @php
                                                                    $attach_arr = explode(',', $message->attachments);
                                                                    $attach_urls = FileUploadHelper::getMultipleDocPath($message->attachments, 'attachment');
                                                                    $attach_docs = explode(",", $attach_urls);
                                                                @endphp
                                                                @foreach ($attach_docs as $attach_doc)
                                                                    <div class="msg_attachment">
                                                                        <a href="{{$attach_doc}}" target="_blank" download class="col-md-4 attachname"> <i class="attachicon fa fa-paperclip"></i> {{$attach_arr[$loop->index]}}</a>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                             <button type="button" class="btn reply_chat_btn offset-md-1 blk_bor_btn mb-2 coach_msg_popup" data-convid="{{$thread->id}}" data-toggle="modal" data-target="#coach_message_modal">Antworten</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-12 mb-3">
                                {{ $threads->appends(request()->query())->links() }}
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn orange_background_btn coach_conv_popup">Nachricht verfassen</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- coach message section end -->
            </div>
        </div>
    </div>
    <!-- coach tab data section end -->
@endsection


@section('scripts')
    <script src="{{ asset('frontend/js/dropzone.js') }}"></script>
    <script>
        Dropzone.autoDiscover = false;
    </script>
    <script>
        $(document).ready(function() {

            $("#messageFrm").validate({
                errorClass: "is-invalid",
                invalidHandler: function(form, validator) {
                    var errors = validator.numberOfInvalids();
                    if (errors) {                    
                        validator.focusInvalid();
                        var error_html   = '';
                        var required_err = false;
                        $.each(validator.errorMap,function(key, data) {
                            if(data.includes('field is required') || data.includes('Feld ist ein Pflichtfeld.')) {
                                if(required_err==false) {
                                    error_html   = 'Bitte füllen Sie alle erforderlichen Felder aus, um fortzufahren. <br>'+error_html;
                                    required_err = true;
                                }
                            } else {
                                //error_html+=key+':'+data+'<br/>';
                                error_html+=data+'<br/>';
                            }
                        });
                        toastr.error(error_html);
                    }
                },
            });
            
            var $hidden_field = $("#attachment");
            $('.coach_msg_popup').click(function() {
                $("#subject").hide();
                $("#conv_id").val($(this).data('convid'));
                showMessageModal();
            });

            $('.coach_conv_popup').click(function(event) {
                $("#subject").show();
                $("#conv_id").val(0);
                showMessageModal();
            });

            function showMessageModal () {
                // Reset fields
                $("#subject").val('');
                $("#message").val('');
                $hidden_field.val('');
                $hidden_field.data('url', '');
                myDropzone.removeAllFiles();
                $('#coach_message_modal').modal('show');
            }

            var myDropzone;
            $("div#dropzone").dropzone({
                addRemoveLinks: true,
                url: "{{ url('coach/upload-attachments') }}",
                previewsContainer: ".dropzone-previews",
                //uploadMultiple: true,
                addRemoveLinks: true,
                success: function(file, data) {
                    if(data.success=='true') {
                        file.upload.filename = data.file_name;
                        var file_names = $hidden_field.val();
                        file_names += file_names==''?data.file_name:','+data.file_name;

                        var file_urls = $hidden_field.data('url');
                        file_urls += file_urls==''?data.file_url:','+data.file_url;
                        $hidden_field.val(file_names);
                        $hidden_field.data('url',file_urls);
                        /*if(file_names=='')
                            $(".dz-message").addClass('w-100');
                        else
                            $(".dz-message").removeClass('w-100');*/
                    } else {
                        this.removeAllFiles();
                        toastr.error(data.message);
                    }
                },
                error: function(file, errorMessage , XMLHttpRequest ) {
                    console.log(errorMessage);
                    this.removeFile(file);
                    if(XMLHttpRequest!=undefined && XMLHttpRequest.status==422) {
                        ajaxValidationError(JSON.parse(XMLHttpRequest.response));
                    } else if(XMLHttpRequest!=undefined && XMLHttpRequest.status==401) {
                        toastr.error(errorMessage.message);
                    } else if(errorMessage=='You can not upload any more files.') {
                        return true;
                    } else if(errorMessage!='') {
                        toastr.error(errorMessage);
                    } else {
                        toastr.error("Something unexpected happended!");
                    }
                    //this.removeAllFiles();
                },
                removedfile: function(file) {
                    var file_name = file.upload.filename;
                    file.previewElement.remove();

                    var file_names = $hidden_field.val();
                    var file_urls  = $hidden_field.data('url');
                    var file_arr   = file_names.split(',');
                    var url_arr    = file_urls.split(',');
                    var new_file_names = new_urls = '';
                    for(let i = 0, length1 = file_arr.length; i < length1; i++) {
                        if(file_arr[i]!=file_name) {
                            new_file_names += new_file_names!=''?','+file_arr[i]:file_arr[i];
                            new_urls       += new_urls!=''?','+url_arr[i]:url_arr[i];
                        } else
                            console.log('removing file '+file_name);
                    }
                    $hidden_field.val(new_file_names);
                    $hidden_field.data('url', new_urls);

                    /*if(new_file_names=='')
                        $(".dz-message").addClass('w-100');
                    else
                        $(".dz-message").removeClass('w-100');*/
                },
                init: function() {
                    myDropzone = this;
                    this.on("sending", function(file, xhr, formData){
                        formData.append("type", "attachment");
                        formData.append("_token", $('input[name="_token"]').val());
                    });
                }
            });

            // Mark Thread as seen
            $('.collapse').on('show.bs.collapse', function () {
                var $parent = $(this).closest('.card');
                var thread_id = $(this).data('thread-id');
                // Total unread count
                var total_unread = $(".unseen_thread_count").text();
                var new_unread = total_unread-1<0?0:total_unread-1;
                $.ajax({
                    url: baseUrl+'/messages/makeseen/'+thread_id,
                    type: 'POST',
                    data: {_token: $('input[name="_token"]').val()},
                })
                .done(function(data) {
                    console.log("success");
                    console.log(data);
                    if(data.success=='true') {
                        // Set read
                        $parent.removeClass('unreadmsg');
                        // Update total unread count
                        $(".unseen_thread_count").text(new_unread);
                    }
                })
                .fail(function(error) {
                    console.log("error");
                    console.log(error);
                })
                .always(function() {
                    console.log("complete");
                });
            });

        });
    </script>
@endsection
