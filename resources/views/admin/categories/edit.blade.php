@extends('layouts.main')

@section('styles')
@endsection

@section('content')
    <!-- begin row -->
    <div class="row">
        <!-- begin col-6 -->
        <div class="col-lg-12">
            <!-- begin panel -->
            <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                <!-- begin panel-body -->
                <div class="panel-body">
                    @include('layouts.section.notifications')
                    <form action="{{  url('admin/categories/'.$category->id) }}" method="POST" id="categoryform" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Icon</label>
                            <div class="col-md-9">
                                <div class="setpreviewimg">
                                    @if($category->icon)
                                        {!! $category->icon !!}
                                    @else
                                        <img src="{{ FileUploadHelper::getDocPath('default.jpg', 'avatar') }}" alt="" class="previewimg m-b-10" id="categoryimg" width="180" height="180">
                                    @endif
                                </div>
                                <input type="file" name="icon" accept="image/svg+xml" class="form-control m-b-5 fileuploaderpreview">
                                <span class="f-w-600 text-black-darker">(180x180 SVG Image without circle)</span>
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Banner</label>
                            <div class="col-md-9">
                                <div class="setpreviewimg" data-width='100%' data-height='520'>
                                    @if($category->banner)
                                        <img src="{{ FileUploadHelper::getDocPath($category->banner, 'cat_banner') }}" alt="" class="previewimg m-b-10" width="100%" height="520">
                                    @else
                                        <img src="{{ FileUploadHelper::getDocPath('default.jpg', 'avatar') }}" alt="" class="previewimg m-b-10" width="100%" height="520">
                                    @endif
                                </div>
                                <input type="file" name="banner" accept="image/*" class="form-control m-b-5 fileuploaderpreview">
                                <span class="f-w-600 text-black-darker">(800x520 Image)</span>
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Title <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="title" name="title" type="text" class="form-control @error('title') is-invalid @enderror" placeholder="Category Title" value="{{ old('title')?old('title'):$category->title }}" required />
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Url Slug <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="url_slug" name="url_slug" type="text" class="form-control @error('url_slug') is-invalid @enderror" placeholder="Category Url" value="{{ old('url_slug')?old('url_slug'):$category->url_slug }}" required />
                                @error('url_slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Short Description <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea name="short_description" id="short_description" class="form-control @error('short_description') is-invalid @enderror" cols="30" rows="10" required>{{ old('short_description')?old('short_description'):$category->short_description }}</textarea>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Description/Content <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" cols="30" rows="10" required>{{ old('description')?old('description'):$category->description }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-7 offset-md-3">
                                <button type="submit" class="btn btn-sm btn-primary m-r-5">Save</button>
                                <a href="{{ url('admin/categories') }}" class="btn btn-sm btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('backend/assets/plugins/ckeditor/ckeditor.js') }}"></script>
<script>
    $(document).ready(function() {

        //CKEDITOR.config.extraPlugins = 'justify';
        // Intialize ckeditor for short_description
        CKEDITOR.replace('short_description', {
            extraPlugins: 'justify',
            customConfig: '',
            removeButtons: 'Subscript,Superscript,Scayt,Link,Unlink,Anchor,Image,Table,Maximize,NumberedList,Outdent,Indent,Blockquote,Styles,Format,About,Source,Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,SpecialChar,HorizontalRule'
        });

        // Intialize ckeditor for description
        CKEDITOR.replace('description', {
            extraPlugins: 'justify',
            customConfig: '',
            toolbarGroups: [
                { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                { name: 'links', groups: [ 'links' ] },
                { name: 'insert', groups: [ 'insert' ] },
                { name: 'forms', groups: [ 'forms' ] },
                { name: 'tools', groups: [ 'tools' ] },
                { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'others', groups: [ 'others' ] },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                { name: 'styles', groups: [ 'styles' ] },
                { name: 'colors', groups: [ 'colors' ] },
                { name: 'about', groups: [ 'about' ] }
            ],
            removeButtons: 'Subscript,Superscript,Scayt,Anchor,SpecialChar,About',
            filebrowserUploadUrl: baseUrl+"/categories/ckeditor/images",
        });

        $("#categoryform").validate({
            errorClass: "is-invalid",
            errorElement: 'div',
            errorPlacement: function(error, element) {
                $(error).addClass('invalid-feedback');
                error.appendTo(element.parent());
            }
        });
    });
</script>
@endsection