@php
    // echo "<pre>"; print_r($result); exit;
$iHtml1 = "";
$iHtml2 = "";
$iHtml3 = "";
$iHtml4 = "";
$iHtml5 = "";
$iHtml6 = "";
$iHtml7 = "";

$glob_checked_img = public_path('img/star_icon-checked.png');
$glob_unchecked_img = public_path('img/star_icon-unchecked.png');

foreach($result as $key=>$value){

    $rating = "";
    
    if($rating_checkbox){
        if($value->rating == 5){
            $rating = "<span><img  src='".$glob_checked_img."' style='width: 13px;'><img  src='".$glob_checked_img."' style='width: 13px;'><img  src='".$glob_checked_img."' style='width: 13px;'><img  src='".$glob_checked_img."' style='width: 13px;'><img  src='".$glob_checked_img."' style='width: 13px;'></span>";
        }else if($value->rating == 4){
            $rating = "<span><img  src='".$glob_checked_img."' style='width: 13px;'><img  src='".$glob_checked_img."' style='width: 13px;'><img  src='".$glob_checked_img."' style='width: 13px;'><img  src='".$glob_checked_img."' style='width: 13px;'><img  src='".$glob_unchecked_img."' style='width: 13px;'></span>";
        }else if($value->rating == 3){
            $rating = "<span><img  src='".$glob_checked_img."' style='width: 13px;'><img  src='".$glob_checked_img."' style='width: 13px;'><img  src='".$glob_checked_img."' style='width: 13px;'><img  src='".$glob_unchecked_img."' style='width: 13px;'><img  src='".$glob_unchecked_img."' style='width: 13px;'></span>";
        }else if($value->rating == 2){
            $rating = "<span><img  src='".$glob_checked_img."' style='width: 13px;'><img  src='".$glob_checked_img."' style='width: 13px;'><img  src='".$glob_unchecked_img."' style='width: 13px;'><img  src='".$glob_unchecked_img."' style='width: 13px;'><img  src='".$glob_unchecked_img."' style='width: 13px;'></span>";
        }else if($value->rating == 1){
            $rating = "<span><img  src='".$glob_checked_img."' style='width: 13px;'><img  src='".$glob_unchecked_img."' style='width: 13px;'><img  src='".$glob_unchecked_img."' style='width: 13px;'><img  src='".$glob_unchecked_img."' style='width: 13px;'><img  src='".$glob_unchecked_img."' style='width: 13px;'></span>";
        }else {
            $rating = "<span>Unrated</span>";
        }
    }

    $iHtml1 .= '<td valign="top" style="width: 21%;">
                    <div style="border: 0;
                    background-color: #fff;
                    width: 100%;
                    border: 1px solid #929292;
                    box-shadow: 0 0 1px #888888;">
                        <div style="
                        height: 60px;
                        padding: 3px 5px;
                        width: 100%;
                        background-color: #bbebff;">
                          <div style="width: 100%;">
                              <span style="color: #000;
                              font-size: 13px;
                              text-align: center;
                              vertical-align: middle;
                              line-height: 15px;
                              width: 100%;
                              display: inline-block;">'.$value->s_name.'</span> 
                          </div>                       
                        </div>
                        <div style="font-size: 12px; padding: 7px;height: 30px;">
                          <span>'.$value->classname.'</span>
                        </div>
                        <div style="font-size: 12px;padding:0px;height: 15px;">
                          '.$rating.'
                        </div>
                      </div>
                </td>';
    $EXITLOAD = ($value->EXITLOAD && $value->EXITLOAD != "0")?number_format((float)$value->EXITLOAD, 2, '.', ''):"-";
    
    $iHtml2 .= '<td valign="top" style="width: 21%;">
                <div style="display: block;">
                <ul style="margin: 0; padding: 0; list-style: none;">';
                
        if(in_array("inception_date", $basic_detail_checkbox) || count($basic_detail_checkbox) == 0){
            $iHtml2 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.date('d-m-Y', strtotime($value->Incept_date)).'</li>';
        }
        if(in_array("fund_type", $basic_detail_checkbox) || count($basic_detail_checkbox) == 0){
            $iHtml2 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$value->classname.'</li>';
        }
        if(in_array("fund_manager", $basic_detail_checkbox) || count($basic_detail_checkbox) == 0){
            $iHtml2 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$value->fund_mgr1.'</li>';
        }
        if(in_array("aum", $basic_detail_checkbox) || count($basic_detail_checkbox) == 0){
            $iHtml2 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.custome_money_format((int)($value->total/100)).'</li>';
        }
        if(in_array("benchmark_index", $basic_detail_checkbox) || count($basic_detail_checkbox) == 0){
            $iHtml2 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$value->IndexName.'</li>';
        }
        if(in_array("expense_ratio", $basic_detail_checkbox) || count($basic_detail_checkbox) == 0){
            $iHtml2 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$value->expratio.'</li>';
        }
        if(in_array("exit_load", $basic_detail_checkbox) || count($basic_detail_checkbox) == 0){
            $iHtml2 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$EXITLOAD.'</li>';
        }
        if(in_array("latest_nav", $basic_detail_checkbox) || count($basic_detail_checkbox) == 0){
            $iHtml2 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$value->navrs.'</li>';
        }
            
        $iHtml2 .= '</ul>
                </div>
                </td>';
    $onemonthret = ($value->onemonthret && $value->onemonthret != "0")?number_format((float)$value->onemonthret, 2, '.', ''):"-";
    $threemonthret = ($value->threemonthret && $value->threemonthret != "0")?number_format((float)$value->threemonthret, 2, '.', ''):"-";
    $sixmonthret = ($value->sixmonthret && $value->sixmonthret != "0")?number_format((float)$value->sixmonthret, 2, '.', ''):"-";
    $oneyrret = ($value->oneyrret && $value->oneyrret != "0")?number_format((float)$value->oneyrret, 2, '.', ''):"-";
    $twoyearret = ($value->twoyearret && $value->twoyearret != "0")?number_format((float)$value->twoyearret, 2, '.', ''):"-";
    $threeyearret = ($value->threeyearret && $value->threeyearret != "0")?number_format((float)$value->threeyearret, 2, '.', ''):"-";
    $fiveyearret = ($value->fiveyearret && $value->fiveyearret != "0")?number_format((float)$value->fiveyearret, 2, '.', ''):"-";
    $tenyret = ($value->tenyret && $value->tenyret != "0")?number_format((float)$value->tenyret, 2, '.', ''):"-";
    $incret = ($value->incret && $value->incret != "0")?number_format((float)$value->incret, 2, '.', ''):"-";
    
    $iHtml3 .= '<td valign="top" style="width: 21%;">
                <div style="display: block;">
                <ul style="margin: 0; padding: 0; list-style: none;">';
                
        if(in_array("1_month", $return_checkbox) || count($return_checkbox) == 0){
            $iHtml3 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$onemonthret.'</li>';
        }     
        if(in_array("3_month", $return_checkbox) || count($return_checkbox) == 0){
            $iHtml3 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$threemonthret.'</li>';
        }     
        if(in_array("6_month", $return_checkbox) || count($return_checkbox) == 0){
            $iHtml3 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$sixmonthret.'</li>';
        }     
        if(in_array("1_year", $return_checkbox) || count($return_checkbox) == 0){
            $iHtml3 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$oneyrret.'</li>';
        }     
        if(in_array("2_year", $return_checkbox) || count($return_checkbox) == 0){
            $iHtml3 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$twoyearret.'</li>';
        }     
        if(in_array("3_year", $return_checkbox) || count($return_checkbox) == 0){
            $iHtml3 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$threeyearret.'</li>';
        }     
        if(in_array("5_year", $return_checkbox) || count($return_checkbox) == 0){
            $iHtml3 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$fiveyearret.'</li>';
        }     
        if(in_array("10_year", $return_checkbox) || count($return_checkbox) == 0){
            $iHtml3 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$tenyret.'</li>';
        }     
        if(in_array("since_inception", $return_checkbox) || count($return_checkbox) == 0){
            $iHtml3 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$incret.'</li>';
        }
        $iHtml3 .= '</ul>
                </div>
                </td>';
    $alpha = ($value->alpha && $value->alpha != "0")?number_format($value->alpha, 2, '.', ''):"-";
    $sharpe = ($value->sharpe && $value->sharpe != "0")?number_format($value->sharpe, 2, '.', ''):"-";
    $sortino = ($value->sortino && $value->sortino != "0")?number_format($value->sortino, 2, '.', ''):"-";
    $beta = ($value->beta && $value->beta != "0")?number_format($value->beta, 2, '.', ''):"-";
    $sd = ($value->sd && $value->sd != "0")?number_format($value->sd, 2, '.', ''):"-";

    $iHtml4 .= '<td valign="top" style="width: 21%;">
                <div style="display: block;">
                <ul style="margin: 0; padding: 0; list-style: none;">';
           
        if(in_array("alpha_ratio", $mf_ratios_checkbox) || count($mf_ratios_checkbox) == 0){
            $iHtml4 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$alpha.'</li>';
        }     
        if(in_array("sharpe_ratio", $mf_ratios_checkbox) || count($mf_ratios_checkbox) == 0){
            $iHtml4 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$sharpe.'</li>';
        }     
        if(in_array("sortino", $mf_ratios_checkbox) || count($mf_ratios_checkbox) == 0){
            $iHtml4 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$sortino.'</li>';
        } 
        if(in_array("beta", $mf_ratios_checkbox) || count($mf_ratios_checkbox) == 0){
            $iHtml4 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$beta.'</li>';
        }
        if(in_array("standard_deviation", $mf_ratios_checkbox) || count($mf_ratios_checkbox) == 0){
            $iHtml4 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$sd.'</li>';
        }
        $iHtml4 .= '</ul>
                </div>
                </td>';
    
    $PB = ($value->PB && $value->PB != "0")?number_format((float)$value->PB, 2, '.', ''):"-";
    $PE = ($value->PE && $value->PE != "0")?number_format((float)$value->PE, 2, '.', ''):"-";
    $Div_Yield = ($value->Div_Yield && $value->Div_Yield != "0")?number_format((float)$value->Div_Yield, 2, '.', ''):"-";
    if($value->tr_mode ==  "times"){
        $turnover_ratio = ($value->turnover_ratio && $value->turnover_ratio != "0")?(int)($value->turnover_ratio*100):"-";
    }else{
        $turnover_ratio = ($value->turnover_ratio && $value->turnover_ratio != "0")?number_format((float)$value->turnover_ratio, 2, '.', ''):"-";
    }
    $ASECT_CODE = ($value->ASECT_CODE && $value->ASECT_CODE != "0")?$value->ASECT_CODE:"-";
    $MCAP = ($value->MCAP && $value->MCAP != "0")?custome_money_format((int)($value->MCAP/100)):"-";
    $large_cap = ($value->large_cap && $value->large_cap != "0")?number_format((float)$value->large_cap, 2, '.', ''):"-";
    $mid_cap = ($value->mid_cap && $value->mid_cap != "0")?number_format((float)$value->mid_cap, 2, '.', ''):"-";
    $small_cap = ($value->small_cap && $value->small_cap != "0")?number_format((float)$value->small_cap, 2, '.', ''):"-";
    $ytm = ($value->ytm && $value->ytm != "0")?number_format((float)$value->ytm, 2, '.', ''):"-";
    $rating_one = ($value->rating_one && $value->rating_one != "0")?number_format((float)$value->rating_one, 2, '.', ''):"-";
    $rating_two = ($value->rating_two && $value->rating_two != "0")?number_format((float)$value->rating_two, 2, '.', ''):"-";
    $rating_three = ($value->rating_three && $value->rating_three != "0")?number_format((float)$value->rating_three, 2, '.', ''):"-";
    $rating_four = ($value->rating_four && $value->rating_four != "0")?number_format((float)$value->rating_four, 2, '.', ''):"-";
    $rating_five = ($value->rating_five && $value->rating_five != "0")?number_format((float)$value->rating_five, 2, '.', ''):"-";
    
    $iHtml5 .= '<td valign="top" style="width: 21%;">
                <div style="display: block;">
                <ul style="margin: 0; padding: 0; list-style: none;">';
        
        if(in_array("portfolio_pb_ratio", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$PB.'</li>';
        }
        if(in_array("portfolio_pe_ratio", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$PE.'</li>';
        }
        if(in_array("dividend_yield", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$Div_Yield.'</li>';
        }
        if(in_array("turnover_ratio", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$turnover_ratio.'</li>';
        }
        if(in_array("no_of_stocks", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$ASECT_CODE.'</li>';
        }
        if(in_array("avg_market_cap", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$MCAP.'</li>';
        }
        if(in_array("large_cap", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$large_cap.'</li>';
        }
        if(in_array("mid_cap", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$mid_cap.'</li>';
        }
        if(in_array("small_cap", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$small_cap.'</li>';
        }
        if(in_array("average_maturity", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$value->avg_mat_num.' '.$value->avg_mat_days.'</li>';
        }
        if(in_array("modified_duration", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$value->mod_dur_num.' '.$value->mod_dur_days.'</li>';
        }
        if(in_array("yield_to_maturity", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$ytm.'</li>';
        }
        if(in_array("sovereign_rating", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$rating_one.'</li>';
        }
        if(in_array("aaa_rated", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$rating_two.'</li>';
        }
        if(in_array("aa_rated", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$rating_three.'</li>';
        }
        if(in_array("a_rated", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$rating_four.'</li>';
        }
        if(in_array("unrated", $portfolio_checkbox) || count($portfolio_checkbox) == 0){
            $iHtml5 .= '<li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;
                border: 1px solid #c0c1c1; font-size: 12px; height: 20px; text-align: center;line-height: 20px;">'.$rating_five.'</li>';
        }
        $iHtml5 .= '</ul>
                </div>
                </td>';

    $iHtml6 .= '<td valign="top" style="width: 21%;">
                <div style="display: block;">
                <ul style="margin: 0; padding: 0; list-style: none;">';
    foreach($value->sector_list as $k=>$val){
        $sector = $k+1;
        if(in_array("sector_".$sector, $sector_checkbox) || count($sector_checkbox) == 0){
            $iHtml6 .= '<li style="margin-bottom: -1px; background-color: #fff;
                    border: 1px solid #c0c1c1; font-size: 12px; height: 32px; text-align: center;line-height: 20px;">
                    <table width="100%" cellspacing="0" cellpadding="0"><tr>
                        <td width="50" style=" margin:0; padding:4px 0 6px 2px; vertical-align:top; 
                        border-right:1px solid #ccc; text-align: left;">'.substr($val->SECT_NAME, 0, 15).'..</td>
                        <td width="15" style="text-align: center;  margin:0; 
                        padding:0px 0; width: 20px;">'.number_format((float)$val->Perc_Hold, 2, '.', '').'</td>
                    </tr></table>
                </li>';
        }
    }
    $iHtml6 .= '</ul>
                </div>
                </td>';

    $iHtml7 .= '<td valign="top" style="width: 21%;">
                <div style="display: block;">
                <ul style="margin: 0; padding: 0; list-style: none;">';
    foreach($value->holding_list as $k=>$val){
        $holding = $k+1;
        $holding_data = "";
        if(strlen($val->compname) > 18){
            $holding_data = substr( $val->compname, 0, 19)."..";
        }else{
            $holding_data = $val->compname;
        }
        
        $iHtml7 .= '<li style="margin-bottom: -1px; background-color: #fff;
                border: 1px solid #c0c1c1; font-size: 12px; height: 32px; text-align: center;line-height: 20px;">
                        <table width="100%" cellspacing="0" cellpadding="0"><tr>
                            <td width="50" style=" margin:0; padding:4px 0 6px 2px; vertical-align:top; border-right:1px solid #ccc; text-align: left;">'.$holding_data.'</td>
                            <td width="15" style="text-align: center;  margin:0; padding:0px 0; width: 20px;">'.number_format((float)$val->holdpercentage, 2, '.', '').'</td>
                        </tr></table>
                </li>';
    }
    $iHtml7 .= '</ul>
                </div>
                </td>';
}

$amf='AMFI-Registered Mutual Fund Distributor';

@endphp
<!DOCTYPE html>
<html>
<head>
<title>MF COMPARISON</title>
<!--'.$val->compname.'-->
<style>
        .comp_percentage {
            text-align: center;
            vertical-align:top;
            line-height:12px;
            display: block;
            background: blue;
        }
        .forscroll {
            text-align: left;
            line-height:12px;
            display: block;
            background: red;
        }
        .comp_bar {
            border-left: 1px solid #dbdbdb;
            display: block;
            background: black;
        }
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #001028;
            text-decoration: none;
        }

        body {
            font-family: 'Poppins', sans-serif;
            position: relative;
            margin: 0 auto;
            color: #001028;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-spacing: 0;
            margin-bottom: 30px;
        }

        table th,
        table td {
            text-align: center;
            padding: 5px 2px;
            font-weight: normal;
            color: #000;
        }

        table {
            margin: 0;
        }

        table th {
            font-weight: bold;
            background: #a9f3ff;
        }

        .table-bordered th, .table-bordered td{
            padding: 4px 1px;
            font-size: 12px;
        }
        table ul li:nth-child(odd) {
            background-color: #fff !important;
        }
        
        table ul li:nth-child(even) {
            background-color: #f0f1f6 !important;
        }
        table td.firsttd  ul li {
            text-align: left;
            line-height: 18px;
        }
        /*table td.firsttd  ul li:nth-child(odd) {*/
        /*    background-color: #fff !important;*/
        /*}*/
        /*table td.firsttd  ul li:nth-child(even) {*/
        /*    background-color: #a2d2f7 !important;*/
        /*}*/
        h1 {
            font-size: 20px !important;
            color: #131f55 !important;
            margin-bottom: 0 !important;
            margin-top: 15px !important;
        }

        .page-break {
            page-break-after: always;
        }

        @page {
            margin-top: 0px
        }

        header {
            border-bottom: 1px solid #d2d2d2;
        }

        footer {
            /*position: fixed;
            bottom: -10px;
            left: 0px;
            right: 0px;
            height: 50px;*/
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 50px;
        }

        .watermark{
            font-size: 60px;
            color: rgba(0,0,0,0.10);
            position: absolute;
            top: 42%;
            left: 26%;
            z-index: 1;
            transform: rotate(-25deg);
            font-weight: 700;
        }
    </style>
</head>
<body>
    
<div style="width: 1340px; max-width:98%; margin: 0 auto;">
    <?php if(count($basic_detail_checkbox) || count($return_checkbox) || count($mf_ratios_checkbox)) { ?>
        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td style="text-align:right; border:0;" align="left" valign="middle">
                        <img style="display:inline-block; height:90px;" src="{{$company_logo}}" alt=""></td>
                </tr>
                </tbody>
            </table>
        </header>
        <div style="padding: 10px 15%;">
            <h1 style="color: #000;font-size:26px;margin-bottom:10px !important;text-align:center;">Mutual Fund Scheme Comparison</h1>
        </div>
        
        <table style="width: 100%;" cellspacing="4" cellpadding="0">
            <tr>
                
                    <td style="width: 16%;">
                        <!--<div style="height: 70px;-->
                        <!--display: block;-->
                        <!--background-color: #bbebff;-->
                        <!--padding: 6px 2px 6px 4px;-->
                        <!--border: 1px solid #929292;-->
                        <!--width: 100%;-->
                        <!--">-->
                        <!--    <h2 style="-->
                        <!--    width: 100%;-->
                        <!--    margin: 10px 0;-->
                        <!--    padding-top:6px;-->
                        <!--    font-size: 16px;-->
                        <!--    letter-spacing: 0;-->
                        <!--    font-weight: normal;-->
                        <!--    line-height: 19px;-->
                        <!--    color: #000000;-->
                        <!--    background-color: #bbebff;-->
                        <!--    text-align: center;-->
                        <!--    font-family: 'Poppins', sans-serif;-->
                        <!--    ">-->
                        <!--        <span>Schemes <br>Comparison</span>-->
                        <!--    </h2>-->
                        <!--</div>-->
                    </td>
                    
                    {!! $iHtml1 !!}
            </tr>
        </table>
        
        <?php if(count($basic_detail_checkbox)){ ?>
            <div style="color: #fff;
                background: #25a8e0;
                padding: 2px 0 0 6px;
                height: 28px;
                line-height: 21px;
                font-size: 14px;
                font-weight: bold;
                margin-top: 10px;
                margin-left: 2px;
                margin-right: 2px;
                text-align: left;
                border: 1px solid #c0c1c1;
                ">
                <span>BASIC DETAILS</span>
            </div>
            
            <table style="width: 100%;" cellspacing="4" cellpadding="0">
                <tr>
                    <td valign="top" style="width: 16%;" class="firsttd">
                        <div style="background-color: #fff;">
                            <ul style="margin: 0; padding: 0; list-style: none;">
                                @if(in_array("inception_date", $basic_detail_checkbox) || count($basic_detail_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        Inception Date
                                    </li>
                                @endif
                                @if(in_array("fund_type", $basic_detail_checkbox) || count($basic_detail_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        Fund Type
                                    </li>
                                @endif
                                @if(in_array("fund_manager", $basic_detail_checkbox) || count($basic_detail_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        Fund Manager
                                    </li>
                                @endif
                                @if(in_array("aum", $basic_detail_checkbox) || count($basic_detail_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        AUM (in Rs. Cr)
                                    </li>
                                @endif
                                @if(in_array("benchmark_index", $basic_detail_checkbox) || count($basic_detail_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        Benchmark Index
                                    </li>
                                @endif
                                @if(in_array("expense_ratio", $basic_detail_checkbox) || count($basic_detail_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        Expense Ratio (%)
                                    </li>
                                @endif
                                @if(in_array("exit_load", $basic_detail_checkbox) || count($basic_detail_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        Exit Load (%)
                                    </li>
                                @endif
                                @if(in_array("latest_nav", $basic_detail_checkbox) || count($basic_detail_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        Latest NAV
                                    </li>
                                @endif
                            </ul>                       
                        </div>
                    </td>
                    
                              
                        
                    {!! $iHtml2 !!}
                    
                    
                </tr>
            </table>
        <?php } ?>
        
        <?php if(count($return_checkbox)){ ?>
            <div style="color: #fff;
                background: #25a8e0;
                padding: 2px 0 0 6px;
                height: 28px;
                line-height: 21px;
                font-size: 14px;
                font-weight: bold;
                margin-top: 10px;
                margin-left: 2px;
                margin-right: 2px;
                text-align: left;
                border: 1px solid #c0c1c1;
                ">
                <span>RETURN (%)</span>
            </div>
            
            <table style="width: 100%;" cellspacing="4" cellpadding="0">
                <tr>
                    <td valign="top" style="width: 16%;" class="firsttd">
                        <div style="background-color: #fff;">
                            <ul style="margin: 0; padding: 0; list-style: none;">
                                @if(in_array("1_month", $return_checkbox) || count($return_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        1 Month
                                    </li>
                                @endif
                                @if(in_array("3_month", $return_checkbox) || count($return_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        3 Month
                                    </li>
                                @endif
                                @if(in_array("6_month", $return_checkbox) || count($return_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        6 Month
                                    </li>
                                @endif
                                @if(in_array("1_year", $return_checkbox) || count($return_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        1 Year
                                    </li>
                                @endif
                                @if(in_array("2_year", $return_checkbox) || count($return_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        2 Year
                                    </li>
                                @endif
                                @if(in_array("3_year", $return_checkbox) || count($return_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        3 Year
                                    </li>
                                @endif
                                @if(in_array("5_year", $return_checkbox) || count($return_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        5 Year
                                    </li>
                                @endif
                                @if(in_array("10_year", $return_checkbox) || count($return_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        10 Year
                                    </li>
                                @endif
                                @if(in_array("since_inception", $return_checkbox) || count($return_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        Since Inception
                                    </li>
                                @endif
                            </ul>                       
                        </div>
                    </td>
                    
                    {!! $iHtml3 !!}
                    
                    
                </tr>
            </table>
        <?php } ?>
            
        <?php if(count($mf_ratios_checkbox)){ ?>
            <div style="color: #fff;
                background: #25a8e0;
                padding: 2px 0 0 6px;
                height: 28px;
                line-height: 21px;
                font-size: 14px;
                font-weight: bold;
                margin-top: 10px;
                margin-left: 2px;
                margin-right: 2px;
                text-align: left;
                border: 1px solid #c0c1c1;
                ">
                <span>MF RATIOS</span>
            </div>
            
            <table style="width: 100%;" cellspacing="4" cellpadding="0">
                <tr>
                    <td valign="top" style="width: 16%;" class="firsttd">
                        <div style="background-color: #fff;">
                            <ul style="margin: 0; padding: 0; list-style: none;">
                                @if(in_array("alpha_ratio", $mf_ratios_checkbox) || count($mf_ratios_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        Alpha Ratio
                                    </li>
                                @endif
                                @if(in_array("sharpe_ratio", $mf_ratios_checkbox) || count($mf_ratios_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        Sharpe Ratio
                                    </li>
                                @endif
                                @if(in_array("sortino", $mf_ratios_checkbox) || count($mf_ratios_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        Sortino
                                    </li>
                                @endif
                                @if(in_array("beta", $mf_ratios_checkbox) || count($mf_ratios_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        Beta
                                    </li>
                                @endif
                                @if(in_array("standard_deviation", $mf_ratios_checkbox) || count($mf_ratios_checkbox) == 0)
                                    <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                        Standard Deviation
                                    </li>
                                @endif
                            </ul>                       
                        </div>
                    </td>
                    
                    {!! $iHtml4 !!}
                </tr>
            </table>
        <?php } ?>
        
        <?php if(!count($portfolio_checkbox) && !$holding_checkbox) { ?>
            <div style="margin-top:10px;">
                @php
                    if($rating_checkbox){
                        $note_data1 = \App\Models\Mfresearch_note::where('type',"mf-screener-compare-with-rating")->first();
                    }else{
                        $note_data1 = \App\Models\Mfresearch_note::where('type',"mf-screener-compare")->first();
                    }
                    if(!empty($note_data1)){
                    @endphp
                    {!!$note_data1->description!!}
                @php } @endphp
            </div>
            <div style="margin-top:10px;">
                Report Date : {{date('d/m/Y')}}
            </div>
        <?php } ?>
    
        <footer style="height: 70px;">
            <p style="margin-left:0%;text-align: center;">
                {!! ($name!='')?$name.'<br>':'' !!}
                {!! ($company_name!='')?$company_name.'<br>':'' !!}
                @php if(isset($amfi_registered)){ @endphp
                {!! ($amfi_registered!='' || $amfi_registered!='0')?$amf.'<br>':'' !!}
                @php } @endphp
                {!! ($email!='')?'Email: '.$email.', ':'' !!}
                @if($website!=''){!! ($phone_no!='')?'M: '.$phone_no.', ':'' !!}@else {!! ($phone_no!='')?'M: '.$phone_no:'' !!} @endif
                {!! ($website!='')?'Website: '.$website:'' !!}
            </p>
        </footer>
        
    <?php } ?>
    
    <?php if(count($portfolio_checkbox)){ ?>
        <?php if(count($basic_detail_checkbox) || count($return_checkbox) || count($mf_ratios_checkbox)) { ?>
            <div class="page-break"></div>
        <?php } ?>
        
        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td style="text-align:right; border:0;" align="left" valign="middle">
                        <img style="display:inline-block; height:90px;" src="{{$company_logo}}" alt=""></td>
                </tr>
                </tbody>
            </table>
        </header>
        
        <div style="padding: 10px 15%;">
            <h1 style="color: #000;font-size:26px;margin-bottom:10px !important;text-align:center;">Mutual Fund Scheme Comparison</h1>
        </div>
        
        <table style="width: 100%;" cellspacing="4" cellpadding="0">
            <tr>
                
                    <td style="width: 16%;">
                        <!--<div style="height: 70px;-->
                        <!--display: block;-->
                        <!--background-color: #bbebff;-->
                        <!--padding: 6px 2px 6px 4px;-->
                        <!--border: 1px solid #929292;-->
                        <!--width: 100%;-->
                        <!--">-->
                        <!--    <h2 style="-->
                        <!--    width: 100%;-->
                        <!--    margin: 10px 0;-->
                        <!--    padding-top:6px;-->
                        <!--    font-size: 16px;-->
                        <!--    letter-spacing: 0;-->
                        <!--    font-weight: normal;-->
                        <!--    line-height: 19px;-->
                        <!--    color: #000000;-->
                        <!--    background-color: #bbebff;-->
                        <!--    text-align: center;-->
                        <!--    font-family: 'Poppins', sans-serif;-->
                        <!--    ">-->
                        <!--        <span>Schemes <br>Comparison</span>-->
                        <!--    </h2>-->
                        <!--</div>-->
                    </td>
                    
                    {!! $iHtml1 !!}
            </tr>
        </table>
        
        <div style="color: #fff;
            background: #25a8e0;
            padding: 2px 0 0 6px;
            height: 28px;
            line-height: 21px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
            margin-left: 2px;
            margin-right: 2px;
            text-align: left;
            border: 1px solid #c0c1c1;
            ">
            <span>PORTFOLIO ATTRIBUTES</span>
        </div>
        
        <table style="width: 100%;" cellspacing="4" cellpadding="0">
            <tr>
                <td valign="top" style="width: 16%;" class="firsttd">
                    <div style="background-color: #fff;">
                        <ul style="margin: 0; padding: 0; list-style: none;">
                            @if(in_array("portfolio_pb_ratio", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    Portfolio P/B Ratio
                                </li>
                            @endif
                            @if(in_array("portfolio_pe_ratio", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    Portfolio P/E Ratio
                                </li>
                            @endif
                            @if(in_array("dividend_yield", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    Dividend Yield
                                </li>
                            @endif
                            @if(in_array("turnover_ratio", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    Turnover Ratio (%)
                                </li>
                            @endif
                            @if(in_array("no_of_stocks", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    No. of Stocks
                                </li>
                            @endif
                            @if(in_array("avg_market_cap", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    Avg Mkt Cap (Rs Cr)
                                </li>
                            @endif
                            @if(in_array("large_cap", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    Large Cap (%)
                                </li>
                            @endif
                            @if(in_array("mid_cap", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    Mid Cap (%)
                                </li>
                            @endif
                            @if(in_array("small_cap", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    Small Cap (%)
                                </li>
                            @endif
                            @if(in_array("average_maturity", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    Average Maturity
                                </li>
                            @endif
                            @if(in_array("modified_duration", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    Modified Duration
                                </li>
                            @endif
                            @if(in_array("yield_to_maturity", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    YTM (%)
                                </li>
                            @endif
                            @if(in_array("sovereign_rating", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    Sovereign Rating
                                </li>
                            @endif
                            @if(in_array("aaa_rated", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    AAA Rated
                                </li>
                            @endif
                            @if(in_array("aa_rated", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    AA Rated
                                </li>
                            @endif
                            @if(in_array("a_rated", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    A Rated
                                </li>
                            @endif
                            @if(in_array("unrated", $portfolio_checkbox) || count($portfolio_checkbox) == 0)
                                <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                    Unrated
                                </li>
                            @endif
                        </ul>                       
                    </div>
                </td>
                
                {!! $iHtml5 !!} 
                
                
            </tr>
        </table>
        
        <!--<div style="color: #fff;-->
        <!--    background: #25a8e0;-->
        <!--    padding: 2px 0 0 6px;-->
        <!--    height: 28px;-->
        <!--    line-height: 21px;-->
        <!--    font-size: 14px;-->
        <!--    font-weight: bold;-->
        <!--    margin-top: 10px;-->
        <!--    margin-left: 2px;-->
        <!--    margin-right: 2px;-->
        <!--    text-align: left;-->
        <!--    border: 1px solid #c0c1c1;-->
        <!--    ">-->
        <!--    <span>TOP 3 Sector (%)</span>-->
        <!--</div>-->
        
        <!--<table style="width: 100%;" cellspacing="4" cellpadding="0">-->
        <!--    <tr>-->
        <!--        <td valign="top" style="width: 16%;" class="firsttd">-->
        <!--            <div style="background-color: #fff;">-->
        <!--                <ul style="margin: 0; padding: 0; list-style: none;">-->
        <!--                    @if(in_array("sector_1", $sector_checkbox) || count($sector_checkbox) == 0)-->
        <!--                        <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">-->
        <!--                            Sector 1-->
        <!--                        </li>-->
        <!--                    @endif-->
        <!--                    @if(in_array("sector_2", $sector_checkbox) || count($sector_checkbox) == 0)-->
        <!--                        <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">-->
        <!--                            Sector 2-->
        <!--                        </li>-->
        <!--                    @endif-->
        <!--                    @if(in_array("sector_3", $sector_checkbox) || count($sector_checkbox) == 0)-->
        <!--                        <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">-->
        <!--                            Sector 3-->
        <!--                        </li>-->
        <!--                    @endif-->
        <!--                </ul>                       -->
        <!--            </div>-->
        <!--        </td>-->
                
        <!--        {!! $iHtml6 !!}-->
        <!--    </tr>-->
        <!--</table>-->
        <?php if(!$holding_checkbox) { ?>
            <div style="margin-top:10px;">
                @php
                    if($rating_checkbox){
                        $note_data1 = \App\Models\Mfresearch_note::where('type',"mf-screener-compare-with-rating")->first();
                    }else{
                        $note_data1 = \App\Models\Mfresearch_note::where('type',"mf-screener-compare")->first();
                    }
                    if(!empty($note_data1)){
                    @endphp
                    {!!$note_data1->description!!}
                @php } @endphp
            </div>
            <div style="margin-top:10px;">
                Report Date : {{date('d/m/Y')}}
            </div>
        <?php } ?>
        <footer style="height: 70px;">
            <p style="margin-left:0%;text-align: center;">
                {!! ($name!='')?$name.'<br>':'' !!}
                {!! ($company_name!='')?$company_name.'<br>':'' !!}
                @php if(isset($amfi_registered)){ @endphp
                {!! ($amfi_registered!='' || $amfi_registered!='0')?$amf.'<br>':'' !!}
                @php } @endphp
                {!! ($email!='')?'Email: '.$email.', ':'' !!}
                @if($website!=''){!! ($phone_no!='')?'M: '.$phone_no.', ':'' !!}@else {!! ($phone_no!='')?'M: '.$phone_no:'' !!} @endif
                {!! ($website!='')?'Website: '.$website:'' !!}
            </p>
        </footer>
        
        
    <?php } ?>
    
    <?php if($holding_checkbox){ ?>
        <?php if(count($portfolio_checkbox) || (count($basic_detail_checkbox) || count($return_checkbox) || count($mf_ratios_checkbox))){ ?>
            <div class="page-break"></div>
        <?php } ?>
        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td style="text-align:right; border:0;" align="left" valign="middle">
                        <img style="display:inline-block; height:90px;" src="{{$company_logo}}" alt=""></td>
                </tr>
                </tbody>
            </table>
        </header>
        <div style="padding: 10px 15%;">
            <h1 style="color: #000;font-size:26px;margin-bottom:10px !important;text-align:center;">Mutual Fund Scheme Comparison</h1>
        </div>
        
        <table style="width: 100%;" cellspacing="4" cellpadding="0">
            <tr>
                
                    <td style="width: 16%;">
                        <!--<div style="height: 70px;-->
                        <!--display: block;-->
                        <!--background-color: #bbebff;-->
                        <!--padding: 6px 2px 6px 4px;-->
                        <!--border: 1px solid #929292;-->
                        <!--width: 100%;-->
                        <!--">-->
                        <!--    <h2 style="-->
                        <!--    width: 100%;-->
                        <!--    margin: 10px 0;-->
                        <!--    padding-top:6px;-->
                        <!--    font-size: 16px;-->
                        <!--    letter-spacing: 0;-->
                        <!--    font-weight: normal;-->
                        <!--    line-height: 19px;-->
                        <!--    color: #000000;-->
                        <!--    background-color: #bbebff;-->
                        <!--    text-align: center;-->
                        <!--    font-family: 'Poppins', sans-serif;-->
                        <!--    ">-->
                        <!--        <span>Schemes <br>Comparison</span>-->
                        <!--    </h2>-->
                        <!--</div>-->
                    </td>
                    
                    {!! $iHtml1 !!}
            </tr>
        </table>
        
        <div style="color: #fff;
            background: #25a8e0;
            padding: 2px 0 0 6px;
            height: 28px;
            line-height: 21px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
            margin-left: 2px;
            margin-right: 2px;
            text-align: left;
            border: 1px solid #c0c1c1;
            ">
            <span>TOP HOLDINGS (%)</span>
        </div>
        
        <table style="width: 100%;" cellspacing="4" cellpadding="0">
            <tr>
                <td valign="top" style="width: 16%;" class="firsttd">
                    <div style="background-color: #fff;">
                        <ul style="margin: 0; padding: 0; list-style: none;">
                            <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                Holding 1
                            </li>
                            <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                Holding 2
                            </li>
                            <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                Holding 3
                            </li>
                            <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                Holding 4
                            </li> 
                            <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                Holding 5
                            </li>
                            <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                Holding 6
                            </li>
                            <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                Holding 7
                            </li>
                            <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                Holding 8
                            </li>
                            <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                Holding 9
                            </li>
                            <li style="padding: 0.75rem 1.25rem; margin-bottom: -1px; background-color: #fff; padding: 6px 2px 6px 4px;border: 1px solid #c0c1c1; font-size: 12px; height: 20px;">
                                Holding 10
                            </li>
                        </ul>                       
                    </div>
                </td>
                
                {!! $iHtml7 !!}
            </tr>
        </table>
    
                  
        <div style="margin-top:10px;">
            @php
                if($rating_checkbox){
                    $note_data1 = \App\Models\Mfresearch_note::where('type',"mf-screener-compare-with-rating")->first();
                }else{
                    $note_data1 = \App\Models\Mfresearch_note::where('type',"mf-screener-compare")->first();
                }
                
                if(!empty($note_data1)){
                @endphp
                {!!$note_data1->description!!}
            @php } @endphp
        </div>
        <div style="margin-top:10px;">
            Report Date : {{date('d/m/Y')}}
        </div>
        <footer style="height: 70px;">
            <p style="margin-left:0%;text-align: center;">
                {!! ($name!='')?$name.'<br>':'' !!}
                {!! ($company_name!='')?$company_name.'<br>':'' !!}
                @php if(isset($amfi_registered)){ @endphp
                {!! ($amfi_registered!='' || $amfi_registered!='0')?$amf.'<br>':'' !!}
                @php } @endphp
                {!! ($email!='')?'Email: '.$email.', ':'' !!}
                @if($website!=''){!! ($phone_no!='')?'M: '.$phone_no.', ':'' !!}@else {!! ($phone_no!='')?'M: '.$phone_no:'' !!} @endif
                {!! ($website!='')?'Website: '.$website:'' !!}
            </p>
        </footer>
    <?php } ?>
</body>
</html>