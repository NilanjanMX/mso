@php
    $notenew = '';
    if(isset($note) && !empty($note)){
        $notenew = str_replace("\r\n", "<br>",$note);
    }elseif(isset($notes) && !empty($notes)){
        $notenew = str_replace("\r\n", "<br>",$notes);
    }
@endphp

@if(isset($is_note) && $is_note == 1 && !empty($notenew))
    <h1 class="midheading">Comments</h1>
    <div class="roundBorderHolder">
        <table class="table table-bordered opCmtTable">
            <tbody>
                <tr>
                    <td>{!! $notenew !!}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endif
