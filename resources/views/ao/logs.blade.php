
<div class="card card-orange card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-solid fa-clipboard-list"> </i> Activity Logs</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        @php 
            // if($ticket->status == 'OPEN') $logs = $ticket->ticket_logs->slice(0, -1);
            // else $logs = $ticket->ticket_logs;
            $logs = $ticket->ticket_logs;
        @endphp
        @foreach($logs as $item)
            <div class="post">
                <div class="user-block">
                    @if($item->user)
                        @if(in_array($item->user->role_id,[1,2,3]))
                            <img class="img-circle img-bordered-sm" src="{{request()->root() . '/images/' . $item->user->username . '.jpg'}}" alt="user image">
                        @else
                            <img class="img-circle img-bordered-sm" src="{{request()->root() . '/images/user.png'}}" alt="user image">
                        @endif
                    @else
                        <img class="img-circle img-bordered-sm" src="{{request()->root() . '/images/user.png'}}" alt="user image">
                    @endif
                    <span class="username">
                        <a class="text-primary">{{$item->user != null ? $item->user->name : config('app.name')}}</a>
                    </span>
                    <span class="description">{{\Carbon\Carbon::parse($item->created_at)->format('M d, Y H:i')}}</span>
                </div>
                <p>{!! str_replace('On Hold the ticket to USER', 'On Hold the ticket to REQUESTOR', $item->log) !!}</p>
                {{-- <p>
                <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                <span class="float-right">
                <a href="#" class="link-black text-sm">
                <i class="far fa-comments mr-1"></i> Comments (5)
                </a>
                </span>
                </p> --}}
            </div>
        @endforeach
    </div>
</div>
