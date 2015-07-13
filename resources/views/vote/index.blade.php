@extends('layout')

@section('content')
    <div class="container" id="main_container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Cédulas</div>

                    <div class="panel-body">
                        <ul class="nav nav-tabs">
                            <?php $count = 1 ?>
                            @foreach($lstCedula as $cedula)
                                <li class="{{ $count == 1 ? 'active':''  }}">
                                    <a data-toggle="tab" href="#ced{{ $count }}">
                                        {{ $cedula->title }}
                                    </a>
                                </li>
                                <?php $count++ ?>
                            @endforeach
                            <li>
                                <a id="btn_resume" data-toggle="tab" href="#ced{{ $count++ }}">Confirmación</a>
                            </li>
                        </ul>

                        {!! Form::open(['url' => route('vote.register'), 'method' => 'POST', 'id'=>'form_vote' ]) !!}
                        <div class="tab-content">
                            <?php $count = 1 ?>
                            @foreach($lstCedula as $cedula)
                                <div id="ced{{ $count }}" class="tab-pane fade in {{ $count == 1 ? 'active':''  }}">
                                    <h3>{{ $cedula->title }}</h3>
                                    <input type="hidden" name="ced_{{ $cedula->code }}" value="" class="ced_val"/>
                                    <input type="hidden" name="ced_desc_{{ $cedula->code }}" value="" class="ced_val"/>
                                    <input type="hidden" name="ced_posc_{{ $cedula->code }}" value="" class="ced_val"/>

                                    <div class="content_vote">
                                        <table border="1px">
                                            @foreach($cedula->lstAgrupol as $agrupol)
                                                <tr>
                                                    <td width="400px">
                                                        <span class="agrupol_desc">{{ $agrupol->description }}</span>
                                                        <input type="hidden" class="code"
                                                               value="{{ $cedula->code . $agrupol->code }}"/>
                                                    </td>
                                                    <td width="150px">
                                                        <img src="{{ asset('/img/simbols/' . $agrupol->code . '.jpg') }}"
                                                             width="120px" height="128px"/>
                                                    </td>
                                                    <td width="150px">
                                                        <div class="radio_vote"></div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>

                                    <hr/>
                                    <a class="btn btn-primary btnNext">Next</a>
                                </div>
                                <?php $count++ ?>
                            @endforeach

                            <div id="ced{{ $count++ }}" class="tab-pane fade in last_resume">
                                <div class="main_resume">
                                    <div class="div_resume">
                                        <table class="tb_resume"></table>
                                    </div>
                                </div>

                                <button type="submit" id="confirmVote">Confirmar Voto</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            {{--</div>--}}

            <script>
                $(document).ready(function () {
                    var message = '{{ $message }}';

                    if (message != '') {
                        alert(message);
                    }

                    $('.radio_vote').click(function () {
                        var contentVote = $(this).parent().parent().parent().parent();
                        var checked = $(this).hasClass('radio_vote_check');

                        $(contentVote).find('.radio_vote').each(function (i, e) {
                            $(e).removeClass('radio_vote_check');
                        });

                        if (checked) {
                            $(this).removeClass('radio_vote_check');
                        } else {
                            $(this).addClass('radio_vote_check');
                        }

                    });

                    $('.btnNext').click(function () {
                        $('.nav-tabs > .active').next('li').find('a').trigger('click');
                        location.href = '#main_container';
                    });

                    $('#btn_resume').click(function () {
                        $('.main_resume').empty();

                        var divResume = "<div class='div_resume'></div>";
                        var tabResume = "<table class='tb_resume'></table>";
                        var lnkChange = "<a href='#' class='btn_change_voto'>Cambiar Voto</a>";

                        $('.tab-content .tab-pane').each(function (i, e) {
                            if (!$(e).hasClass('last_resume')) {
                                var tempDivResume = $(divResume);
                                var tempTabResume = $(tabResume);

                                var title = $(e).find('h3').clone();
                                var row = $(e).find('.radio_vote_check').parent().parent().clone();

                                if (row.html() != null) {
                                    var lastTd = row.find('td').last();
                                    lastTd.remove();
                                } else {
                                    row = "<tr><td>Voto en Blanco</td></tr>";
                                }

                                tempDivResume.append(title);
                                tempDivResume.append(lnkChange);
                                tempTabResume.append(row);
                                tempDivResume.append(tempTabResume)

                                $('.main_resume').append(tempDivResume);
                            }
                        });
                    });

                    $('#form_vote').submit(function (e) {
                        if (confirm('¿Esta seguro que desea confirmar su voto?')) {
                            $('.ced_val').each(function (i, e) {
                                $(e).val('');
                            });

                            $('.tab-content .tab-pane').each(function (i, e) {
                                var inputCode = $(e).find('h3').next('input');
                                var inputDesc = inputCode.next('input');
                                var inputPosc = inputDesc.next('input');
                                var row = $(e).find('.radio_vote_check').parent().parent();
                                var agrupol = row.find('.agrupol_desc').text();
                                var code = row.find('.code').val();


                                var count = 1;
                                var posc;

                                $(e).find('.radio_vote').each(function (i, e) {
                                    if ($(e).hasClass('radio_vote_check')) {
                                        posc = count;
                                    }
                                    count++;
                                });

                                inputCode.val(code);
                                inputDesc.val(agrupol);
                                inputPosc.val(posc);
                            });
                        } else {
                            e.preventDefault();
                        }
                    });
                });
            </script>
@endsection