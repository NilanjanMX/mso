@php
    $notenew = '';
    if(isset($note) && !empty($note)){
        $notenew = str_replace("\r\n", "<br>",$note);
    }elseif(isset($notes) && !empty($notes)){
        $notenew = str_replace("\r\n", "<br>",$notes);
    }
@endphp

@if(isset($is_note) && $is_note == 1 && !empty($notenew))
    <div style="padding: 0 0%;">
        <h1 class="pdfTitie">Comments</h1>
        <div class="roundBorderHolder pdfCmtTable">
            <table>
                <tbody>
                    <tr>
                            <td>{!!$notenew!!}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endif