@extends('layouts.main')

@section('styles')
    <!-- ==================   MESSAGE JS   ================== -->
    <link href="{{ asset('backend/css/message.css')}}" rel="stylesheet" />
@endsection

@section('content')
@csrf
<div class="container-fluid white_back articlewrap">
    <div class="row">
        <div id="frame">
            <div id="sidepanel">
                <div id="profile">
                    <div class="wrap">
                        <img alt="{{ $user->first_name.' '.$user->last_name }}" class="online" id="profile-img" src="{{url('avatar/medium/'.$user->avatar)}}"/>
                        <p>{{ $user->first_name.' '.$user->last_name }}</p>
                    </div>
                </div>
                <div id="contacts">
                    <ul>
                        @foreach($members as $member)
                        <li class="contact" contact-id="{{ $member['id'] }}" id="contact-{{ $member['id'] }}">
                            <div class="wrap">
                                <img alt="{{ $member['name'] }}" class="contact-pic" src="{{url('avatar/medium/'.$member['avatar'])}}"/>
                                <div class="meta">
                                    <div class="name contactdetails">
                                        <p class="contact-name">{{ $member['name'] }}</p>
                                        <p class="preview">
                                            @if(!empty($member['messages']))
                                                @php
                                                    $memberMessages = $member['messages'];
                                                    $lastMessage    = end($memberMessages);
                                                @endphp
                                                @if($lastMessage)
                                                    @if($lastMessage->user_id == $user->id)
                                                    <span>
                                                        You:
                                                    </span>
                                                    @endif
                                                    {{ $lastMessage->message }}
                                                @endif
                                            @endif
                                        </p>
                                    </div>
                                    @if(isset($member['unseen_count']) && ($member['unseen_count'] > 0))
                                        <p class="msgcnt">{{ $member['unseen_count'] }}</p>
                                    @endif
                                    <div class="clearfix">
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="content">
                <div class="contact-profile">
                    <img alt="" id="main-contact-pic" src=""/>
                    <p id="main-contact-name"></p>
                </div>
                @foreach($members as $member)
                <div class="messages" id="messages-{{ $member['id'] }}" style="display:none;" user-id="{{ $member['id'] }}">
                    @if((isset($member['message_count'])) && ($member['message_count'] > 5))
                        <div class="load-more">
                            {{-- <button class="">Load More</button> --}}
                            <a href="javascript:;" class="btn btn-success btn-icon btn-circle btn-lg">  <i class="fas fa-arrow-up"></i> <span class="loadmoretext">Show Previous Messages</span>
                            </a>
                        </div>
                    @endif
                    <ul>
                        @if(!empty($member['messages']))
                            @foreach($member['messages'] as $message)
                                <li class="@if($message->user_id == $user->id) sent @else replies @endif" message-id="{{ $message->id}}">
                                    @if($message->user_id == $user->id)
                                        <img src="{{ url('avatar/medium/'.$user->avatar) }}" />
                                    @else
                                        <img src="{{ url('avatar/medium/'.$member['avatar']) }}" />
                                    @endif
                                <p>{{ $message->message }}</p>
                                <div class="clearfix"></div>
                                <span>{{ date('d/m/Y h:iA', strtotime($message->created_at)) }}</span>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                @endforeach
                <div class="message-input" style="display:none;">
                    <div class="wrap">
                        <input placeholder="Write your message..." type="text"/>
                        <button class="submit">
                            <i aria-hidden="true" class="fa fa-paper-plane">
                            </i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <!-- ==================   MESSAGE JS   ================== -->
    <script src="{{ asset('backend/js/message.js') }}"></script>
@endsection
