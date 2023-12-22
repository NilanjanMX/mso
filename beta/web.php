<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
|
|use App\Http\Controllers\PDFController;

*/
use App\Http\Controllers\SwpCheck;

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    
    return 'cache cleared';
});

Route::get('/test-email2', function() {
  Mail::send([], [], function ($message) {
  $message->to('subhasishsamanta28@gmail.com')
    ->subject('Masterstroke Test Mail')
    ->setBody('Hi, Welcome User!')
    ->setBody('<h1>Hi, welcome user!</h1>', 'text/html');
});
});


Route::get('/send-mail', 'HomeController@sendMail');
Route::get('/update-user-free', 'RandhirController@update_user_free');
Route::get('/update-user-paid', 'RandhirController@update_user_paid');
Route::get('/update_membership_table', 'RandhirController@update_membership_table');

Route::get('/test-email', 'Frontend\HomeController@test_email');
Route::get('/cron/logout-user', 'MfscannerCronController@logout_user');
Route::get('/cron/updateaccordtable', 'MfscannerCronController@updateaccordtable');
Route::get('/delete-package', 'MfscannerCronController@delete_package');
Route::post('ckeditor/upload', 'MfscannerCronController@upload')->name('ckeditor.upload');
Route::get('upload-avg', 'MfscannerCronController@updateAvg');

Route::get('update-mf-category-wise', 'UserCronController@updateMfCategoryWise');
Route::get('update-mf-category-wise/{year}', 'UserCronController@updateMfCategoryWiseYear');
Route::get('update-navhist-data', 'UserCronController@updateNavhistData');
Route::get('update-mf-portfolio/{date}', 'UserCronController@updateMfPortfolio');
Route::get('update-mf-portfolio-data-from-file/{count_start}', 'UserCronController@updateMFPortfolioDataFromFile');
Route::get('update-mf-portfolio/{date}/{count}', 'UserCronController@updateMfPortfolioCount');

Route::get('update-mailerlite/{count}', 'MailerliteController@mailerlite');
Route::get('mailerlite-create-group', 'MailerliteController@create_group');

Route::get('renewal-notification-mail', 'UserNotificationController@renewalNotificationMail');
Route::get('send-mail-to-expired-user', 'UserNotificationController@sendMailToExpiredUser');
Route::get('cron-premium-membership-trial', 'UserNotificationController@premiumMembershipTrial');
Route::get('cron-today-order-membership', 'UserNotificationController@mailSendTodayOrderMembership');
Route::get('cron-multi-user', 'UserNotificationController@update_multi_user');


Route::get('trigger-cron', 'TriggerController@trigger_cron');
Route::get('trigger-cron-index', 'TriggerController@trigger_cron_index');
Route::get('trigger-cron-mso', 'TriggerController@trigger_cron_mso');


Route::get('mf-stocks-bought-update/{count}', 'MfresearchMoreController@mfStocksBoughtUpdate');
Route::get('mf-stocks-held-update/{count}/{type}', 'MfresearchMoreController@mfStocksHeldUpdate');
Route::get('update-mf-debt-helds/{count}', 'MfresearchMoreController@updateMfDebtHelds');
Route::get('update-mf-portfolio-analysis/{count}', 'MfresearchMoreController@updateMfPortfolioAnalysis');

Route::get('update-mf-navhist-month-end/{count}', 'MfresearchReturnController@update_mf_navhist_month_end');
Route::get('update-rolling-return/{count}/{type}/{indedx}', 'MfresearchReturnController@update_mf_rolling_return');
Route::get('update-sip-return/{count}/{type}', 'MfresearchSipReturnController@update_mf_sip_return');
Route::get('update-sip-return-post', 'MfresearchSipReturnController@update_mf_sip_return_post');
Route::get('mf-navhist-1', 'MfresearchReturnController@mf_navhist');
Route::get('update-mf-navhist/{count}', 'MfresearchReturnController@update_mf_navhist');
Route::get('update-mf-navhist-demo/{count}', 'MfresearchReturnController@update_mf_navhist_demo');
Route::get('delete-mf-navhist/{count}', 'MfresearchReturnController@delete_mf_navhist');
Route::get('cron-rolling-return', 'MfresearchReturnController@cron_rolling_return');
Route::get('cron-rolling-return-1', 'MfresearchReturnController@cron_rolling_return1');
Route::get('cron-rolling-return-demo/{count}/{type}', 'MfresearchReturnDemoController@rolling_return_demo');
Route::get('update-mf-navhist-demo-1/{count}', 'MfresearchReturnDemoController@update_nav_data');
Route::get('update-rolling-return-new', 'MfresearchReturnDemoController@rolling_return');
Route::get('update-rolling-return-new2', 'MfresearchReturnDemoController@rolling_return2');
Route::get('update-rolling-return-new3', 'MfresearchReturnDemoController@rolling_return3');
Route::get('update-rolling-return-new4', 'MfresearchReturnDemoController@rolling_return4');

Route::get('mso-ratting-cron/{type}', 'MFRattingController@cron');
Route::get('mso-ratting-point-history', 'MFRattingController@history');
Route::get('mso-ratting-point-score', 'MFRattingController@score');
Route::get('mso-ratting-point-score-history', 'MFRattingController@score_history');
Route::get('mso-ratting', 'MFRattingController@update_rating');
Route::get('mso-ratting-update-user-data', 'UserNotificationController@update_user_data');

Route::get('file-update-mf-portfolio/{count_start}', 'CronrandhirController@update_mf_portfolio');

Route::get('/update-accord-data', 'CronController@update_accord_data');
Route::get('/update-accord-data1', 'CronController@update_accord_data_1');
Route::get('/update-accord-data2', 'CronController@update_accord_data_2');
Route::get('/update-accord-data3', 'CronController@update_accord_data_3');
Route::get('/update-accord-data4', 'CronController@update_accord_data_4');
Route::get('/update-accord-data5', 'CronController@update_accord_data_5');
Route::get('/update-accord-data6', 'CronController@update_accord_data_6');
Route::get('/update-accord-data7', 'CronController@update_accord_data_7');
Route::get('/update-accord-data8', 'CronController@update_accord_data_8');
Route::get('/update-accord-data9', 'CronController@update_accord_data_9');
Route::get('/update-accord-data10', 'CronController@update_accord_data_10');
Route::get('/update-accord-data11', 'CronController@update_accord_data_11');
Route::get('/update-accord-data12', 'CronController@update_accord_data_12');
Route::get('/update-accord-data13', 'CronController@update_accord_data_13');
Route::get('/update-accord-data14', 'CronController@update_accord_data_14');
Route::get('/update-accord-data15', 'CronController@update_accord_data_15');
Route::get('/update-accord-data16', 'CronController@update_accord_data_16');
Route::get('/update-accord-data17', 'CronController@update_accord_data_17');
Route::get('/update-accord-data18', 'CronController@update_accord_data_18');
Route::get('/update-accord-data19', 'CronController@update_accord_data_19');
Route::get('/update-accord-data20', 'CronController@update_accord_data_20');
Route::get('/update-accord-data21', 'CronController@update_accord_data_21');
Route::get('/update-accord-data22', 'CronController@update_accord_data_22');
Route::get('/update-accord-data23', 'CronController@update_accord_data_23');
Route::get('/update-accord-data24', 'CronController@update_accord_data_24');
Route::get('/update-accord-data25', 'CronController@update_accord_data_25');
Route::get('/update-accord-data26', 'CronController@update_accord_data_26');
Route::get('/update-accord-data27', 'CronController@update_accord_data_27');
Route::get('/update-accord-data28', 'CronController@update_accord_data_28');
Route::get('/update-accord-data29', 'CronController@update_accord_data_29');
Route::get('/update-accord-data30', 'CronController@update_accord_data_30');
Route::get('/update-accord-data31', 'CronController@update_accord_data_31');
Route::get('/update-accord-data32', 'CronController@update_accord_data_32');
Route::get('/update-accord-data33', 'CronController@update_accord_data_33');
Route::get('/update-accord-data34', 'CronController@update_accord_data_34');
Route::get('/update-accord-data35', 'CronController@update_accord_data_35');
Route::get('/update-accord-data36', 'CronController@update_accord_data_36');
Route::get('/update-accord-data37', 'CronController@update_accord_data_37');
Route::get('/update-accord-data38', 'CronController@update_accord_data_38');
Route::get('/update-accord-data39', 'CronController@update_accord_data_39');
Route::get('/update-accord-data40', 'CronController@update_accord_data_40');
Route::get('/update-accord-data41', 'CronController@update_accord_data_41');
Route::get('/update-accord-data42', 'CronController@update_accord_data_42');
Route::get('/update-accord-data43', 'CronController@update_accord_data_43');
Route::get('/update-accord-data44', 'CronController@update_accord_data_44');
Route::get('/update-accord-data45', 'CronController@update_accord_data_45');
Route::get('/update-accord-data46', 'CronController@update_accord_data_46');
Route::get('/update-accord-data47', 'CronController@update_accord_data_47');
Route::get('/update-accord-data48', 'CronController@update_accord_data_48');
Route::get('/update-accord-data49', 'CronController@update_accord_data_49');
Route::get('/update-accord-data50', 'CronController@update_accord_data_50');
Route::get('/update-accord-data51', 'CronController@update_accord_data_51');
Route::get('/update-accord-data52', 'CronController@update_accord_data_52');
Route::get('/update-accord-data53', 'CronController@update_accord_data_53');
Route::get('/update-accord-data54', 'CronController@update_accord_data_54');
Route::get('/update-accord-data55', 'CronController@update_accord_data_55');
Route::get('/update-accord-data56', 'CronController@update_accord_data_56');
Route::get('/update-accord-data57', 'CronController@update_accord_data_57');
Route::get('/update-accord-data58', 'CronController@update_accord_data_58');
Route::get('/update-accord-data59', 'CronController@update_accord_data_59');
Route::get('/update-accord-data60', 'CronController@update_accord_data_60');
Route::get('/update-accord-data61', 'CronController@update_accord_data_61');
Route::get('/update-accord-data62', 'CronController@update_accord_data_62');
Route::get('/update-accord-data63', 'CronController@update_accord_data_63');
Route::get('/update-accord-data64', 'CronController@update_accord_data_64');
Route::get('/update-accord-data65', 'CronController@update_accord_data_65');
Route::get('/update-accord-data66', 'CronController@update_accord_data_66');
Route::get('/update-accord-data67', 'CronController@update_accord_data_67');
Route::get('/update-accord-data68', 'CronController@update_accord_data_68');
Route::get('/update-accord-data69', 'CronController@update_accord_data_69');
Route::get('/update-accord-data70', 'CronController@update_accord_data_70');
Route::get('/update-accord-data71', 'CronController@update_accord_data_71');
Route::get('/update-accord-data72', 'CronController@update_accord_data_72');
Route::get('/update-accord-data73', 'CronController@update_accord_data_73');
Route::get('/update-accord-data74', 'CronController@update_accord_data_74');
Route::get('/update-accord-data75', 'CronController@update_accord_data_75');
Route::get('/update-accord-data75', 'CronController@update_accord_data_75');


Route::get('/accord-amc-aum/{date}', 'AccordLinkController@amc_aum');
Route::get('/accord-amc-mst/{date}', 'AccordLinkController@amc_mst');
Route::get('/accord-amc-paum/{date}', 'AccordLinkController@amc_paum');
Route::get('/accord-avg-maturity/{date}', 'AccordLinkController@avg_maturity');
Route::get('/accord-avg-scheme-aum/{date}', 'AccordLinkController@avg_scheme_aum');
Route::get('/accord-companymaster/{date}', 'AccordLinkController@companymaster');
Route::get('/accord-companymcap/{date}', 'AccordLinkController@companymcap');
Route::get('/accord-dailyfundmanage/{date}', 'AccordLinkController@dailyfundmanage');
Route::get('/accord-expenceratio/{date}', 'AccordLinkController@expenceratio');
Route::get('/accord-fundmanager-mst/{date}', 'AccordLinkController@fundmanager_mst');
Route::get('/accord-index-mst/{date}', 'AccordLinkController@index_mst');
Route::get('/accord-industry-mst/{date}', 'AccordLinkController@industry_mst');
Route::get('/accord-portfolio-inout/{date}', 'AccordLinkController@portfolio_inout');
Route::get('/accord-scheme-aum/{date}', 'AccordLinkController@scheme_aum');
Route::get('/accord-scheme-details/{date}', 'AccordLinkController@scheme_details');
Route::get('/accord-scheme-paum/{date}', 'AccordLinkController@scheme_paum');
Route::get('/accord-scheme-master/{date}', 'AccordLinkController@scheme_master');
Route::get('/accord-sclass-mst/{date}', 'AccordLinkController@sclass_mst');
Route::get('/accord-asect-mst/{date}', 'AccordLinkController@asect_mst');
Route::get('/accord-gsecmaster/{date}', 'AccordLinkController@gsecmaster');
Route::get('/accord-cust-mst/{date}', 'AccordLinkController@cust_mst');
Route::get('/accord-option-mst/{date}', 'AccordLinkController@option_mst');
Route::get('/accord-plan-mst/{date}', 'AccordLinkController@plan_mst');
Route::get('/accord-rt-mst/{date}', 'AccordLinkController@rt_mst');
Route::get('/accord-div-mst/{date}', 'AccordLinkController@div_mst');
Route::get('/accord-type-mst/{date}', 'AccordLinkController@type_mst');
Route::get('/accord-loadtype-mst/{date}', 'AccordLinkController@loadtype_mst');
Route::get('/accord-scheme-objective/{date}', 'AccordLinkController@scheme_objective');
Route::get('/accord-amc-keypersons/{date}', 'AccordLinkController@amc_keypersons');
Route::get('/accord-mf-sip/{date}', 'AccordLinkController@mf_sip');
Route::get('/accord-mf-swp/{date}', 'AccordLinkController@mf_swp');
Route::get('/accord-mf-stp/{date}', 'AccordLinkController@mf_stp');
Route::get('/accord-scheme-rtcode/{date}', 'AccordLinkController@scheme_rtcode');
Route::get('/accord-scheme-index_part/{date}', 'AccordLinkController@scheme_index_part');
Route::get('/accord-schemeisinmaster/{date}', 'AccordLinkController@schemeisinmaster');
Route::get('/accord-scheme-rgess/{date}', 'AccordLinkController@scheme_rgess');
Route::get('/accord-schemeload/{date}', 'AccordLinkController@schemeload');
Route::get('/accord-currentnav/{date}', 'AccordLinkController@currentnav');
Route::get('/accord-divdetails/{date}', 'AccordLinkController@divdetails');
Route::get('/accord-sect-allocation/{date}', 'AccordLinkController@sect_allocation');
Route::get('/accord-mf-return/{date}', 'AccordLinkController@mf_return');
Route::get('/accord-mf-abs-return/{date}', 'AccordLinkController@mf_abs_return');
Route::get('/accord-classwisereturn/{date}', 'AccordLinkController@classwisereturn');
Route::get('/accord-mf-ans-return/{date}', 'AccordLinkController@mf_ans_return');
Route::get('/accord-mf-ratio/{date}', 'AccordLinkController@mf_ratio');
Route::get('/accord-mf-ratios_defaultbm/{date}', 'AccordLinkController@mf_ratios_defaultbm');
Route::get('/accord-bm-absolutereturn/{date}', 'AccordLinkController@bm_absolutereturn');
Route::get('/accord-bm-annualisedreturn/{date}', 'AccordLinkController@bm_annualisedreturn');
Route::get('/accord-scheme-eq-details/{date}', 'AccordLinkController@scheme_eq_details');
Route::get('/accord-fmp-yielddetails/{date}', 'AccordLinkController@fmp_yielddetails');
Route::get('/accord-fvchange/{date}', 'AccordLinkController@fvchange');
Route::get('/accord-mergedschemes/{date}', 'AccordLinkController@mergedschemes');
Route::get('/accord-mfbulkdeals/{date}', 'AccordLinkController@mfbulkdeals');
Route::get('/accord-scheme-assetalloc/{date}', 'AccordLinkController@scheme_assetalloc');
Route::get('/accord-scheme-name-change/{date}', 'AccordLinkController@scheme_name_change');
Route::get('/accord-extra-return/{date}', 'AccordLinkController@extra_return');
Route::get('/accord-extra-ans-return/{date}', 'AccordLinkController@extra_ans_return');
Route::get('/accord-extra-abs-return/{date}', 'AccordLinkController@extra_abs_return');
Route::get('/accord-index-extra_returns/{date}', 'AccordLinkController@index_extra_returns');
Route::get('/accord-navhist-hl/{date}', 'AccordLinkController@navhist_hl');
Route::get('/accord-navhist/{date}', 'AccordLinkController@navhist');
Route::get('/accord-mf-portfolio/{date}/{count_start}', 'AccordLinkController@mf_portfolio');

Route::get('/update-accord-data-from-file', 'AccordLinkController@updateAccordDataFromFile');
Route::get('/mf-scanner-delete-inactive-scheme', 'AccordLinkController@deleteInactiveScheme');

Route::get('/start-accord-cron-night', 'AccordLinkController@startCronNight');
Route::get('/start-accord-cron-morning', 'AccordLinkController@startCronMorning');
Route::get('/update-mf-scanner-cron-new', 'AccordLinkController@updateMFScreener');

Route::get('/update-mf-scanner-cron', 'AccordController@updateMFScreener');
Route::get('/update-mf-scanner-cron1', 'AccordController@cron1');
Route::get('update-avg-cron', 'AccordController@updateAvgCron');

// Example Routes
//Route::get('/', 'Frontend\HomeController@index');
//Route::get('/home', 'Frontend\HomeController@index');
//Route::get('/user-import', 'HomeController@userImport');
Route::get('/index-new', 'Frontend\HomeController@index_new');

Route::get('/', 'Frontend\HomeController@index_new');
Route::get('/page-one', 'Frontend\HomeController@blankone');
Route::get('/page-two', 'Frontend\HomeController@blanktwo');
Route::get('/home', 'Frontend\HomeController@index_new');
Auth::routes();

Route::get('/about-us', 'Frontend\PageController@aboutUs')->name('frontend.aboutUs');
Route::get('/disclaimers', 'Frontend\PageController@disclaimers')->name('frontend.disclaimers');
Route::get('/contact-us', 'Frontend\PageController@contactUs')->name('frontend.contactUs');
Route::get('/coaching', 'Frontend\PageController@coaching')->name('frontend.coaching');
Route::post('coaching/send', 'Frontend\PageController@coachingSendMail')->name('frontend.coachingMail');
Route::get('/sales-presenters', 'Frontend\PageController@salesPresenters')->name('frontend.sales-presenters');
Route::get('/gallery', 'Frontend\PageController@gallery')->name('frontend.gallery');
Route::get('/whatsapp-broadcast', 'Frontend\PageController@whatsapp_broadcast')->name('frontend.whatsapp-broadcast');

Route::get('/terms-conditions', 'Frontend\PageController@terms_condition')->name('frontend.terms-conditions');
Route::get('/privacy-policy', 'Frontend\PageController@privacy_policy')->name('frontend.privacy-policy');
Route::get('/website-tree', 'Frontend\PageController@websiteTree')->name('frontend.websiteTree');

//SUCCESS STORIES
Route::get('/success-stories', 'Frontend\SuccessstoryController@index')->name('frontend.success-stories');
Route::get('/submit-success-stories', 'Frontend\SuccessstoryController@submitSuccessStory')->name('frontend.submit-success-stories');
Route::get('/success-stories/{slug}', 'Frontend\SuccessstoryController@details')->name('frontend.success-stories-details');

Route::get('/success-story/like/{id}', 'Frontend\SuccessstoryController@like');
Route::post('/success-story/submit/', 'Frontend\SuccessstoryController@successstory_submit')->name('frontend.success-stories.successstory_submit');

//Thoughts
Route::get('/thoughts', 'Frontend\thoughts\ThoughtController@thoughts')->name('frontend.thoughts');
Route::get('/thoughts/most-like', 'Frontend\thoughts\ThoughtController@mostLike')->name('frontend.thoughts.mostlike');
Route::get('/thoughts/random', 'Frontend\thoughts\ThoughtController@random')->name('frontend.thoughts.random');
Route::get('/thought/like/{id}', 'Frontend\thoughts\ThoughtController@like');

Route::get('/thoughts/category/{slug}', 'Frontend\thoughts\ThoughtController@get_category')->name('frontend.thoughts.category');;
Route::get('/thoughts/category/most-like/{slug}', 'Frontend\thoughts\ThoughtController@category_mostLike')->name('frontend.thoughts.category.mostlike');
Route::get('/thoughts/category/random/{slug}', 'Frontend\thoughts\ThoughtController@category_random')->name('frontend.thoughts.category.random');

//Blog
Route::get('/blogs', 'Frontend\BlogController@index')->name('frontend.blog');
Route::get('/blog/{slug}', 'Frontend\BlogController@blog_details')->name('frontend.blog_details');
Route::get('/blogs/category/{slug}', 'Frontend\BlogController@get_category_post')->name('frontend.blogs.category');;

//Blog Comment
Route::post('/blogcomment','Frontend\BlogcommentController@blog_comment')->name('frontend.blogcomment');

//Articles

Route::get('/articles', 'Frontend\ArticleController@index')->name('frontend.article');
Route::get('/articles/{slug}', 'Frontend\ArticleController@article_details')->name('frontend.article_details');

//Articles Comment
Route::post('/articlecomment','Frontend\ArticlecommentController@article_comment')->name('frontend.articlecomment');

//News

Route::get('/news', 'Frontend\NewsController@index')->name('frontend.news');
Route::get('/news/{slug}', 'Frontend\NewsController@news_details')->name('frontend.news_details');

// Videos

/*Route::get('/marketting-free-videos', 'Frontend\VideoController@free_videos')->name('frontend.free-videos');
Route::get('/marketting-free-videos-most-viewed', 'Frontend\VideoController@free_most_viewed_videos')->name('frontend.free-videos-most-viewed');
Route::get('/marketting-paid-videos', 'Frontend\VideoController@paid_videos')->name('frontend.paid-videos')->middleware(['verifyMembership']);;
Route::get('/marketting-free-video/{slug}', 'Frontend\VideoController@free_videos_details')->name('frontend.marketting-free-video-details');*/

Route::get('/short-videos', 'Frontend\VideoController@free_videos')->name('frontend.free-videos');
Route::get('/short-videos-most-viewed', 'Frontend\VideoController@free_most_viewed_videos')->name('frontend.free-videos-most-viewed');
Route::get('/marketting-paid-videos', 'Frontend\VideoController@paid_videos')->name('frontend.paid-videos')->middleware(['verifyMembership']);
Route::get('/short-video/{slug}', 'Frontend\VideoController@free_videos_details')->name('frontend.marketting-free-video-details');

// IFA Tools

Route::get('/tools', 'Frontend\IfatoolsController@index')->name('frontend.ifa-tools');

// Download Tool

Route::get('/download-tool', 'Frontend\DownloadtoolController@index')->name('frontend.downloadtool');
Route::get('/download-tool/{slug}', 'Frontend\DownloadtoolController@details')->name('frontend.downloadtool.details');
Route::get('/download-tool/download/{id}/{file}', 'Frontend\DownloadtoolController@downloads')->name('frontend.downloadtool.downloads');

//CLIENT OBJECTION HANDLING
Route::get('/client-objection-handling', 'Frontend\ClientobjectionhandelingController@index')->name('frontend.client-objection-handling');
Route::get('/client-objection-handling/view/{id}', 'Frontend\ClientobjectionhandelingController@views');
Route::get('/client-objection-handling/like/{id}', 'Frontend\ClientobjectionhandelingController@likes');
Route::get('/client-objection-handling/details/{slug}', 'Frontend\ClientobjectionhandelingController@details')->name('frontend.client-objection-handling.details');

//BOOK RECOMMENDATIONS FOR IFAS
Route::get('/book-recomendation-for-ifas', 'Frontend\BookrecommendationController@index')->name('frontend.book-recomendation-for-ifas');
Route::get('/book-recomendation-for-ifas/download/{id}/{language}/{file}', 'Frontend\BookrecommendationController@downloads')->name('frontend.book-recomendation-for-ifas.downloads');

//Products Suitability
Route::get('/products-suitability', 'Frontend\ProductsuitablityController@index')->name('frontend.products-suitability');
Route::get('/products-suitability/view/{id}', 'Frontend\ProductsuitablityController@views');
Route::get('/products-suitability/like/{id}', 'Frontend\ProductsuitablityController@likes');
Route::get('/products-suitability/details/{slug}', 'Frontend\ProductsuitablityController@details')->name('frontend.products-suitability.details');

//MUTUAL FUND PORTFOLIO SOFTWARES

Route::get('/mutual-fund-portfolio-softwares', 'Frontend\PageController@mutualFundPortfolioSoftwares')->name('frontend.mutual-fund-portfolio-softwares');

//LIST OF NATIONAL DISTRIBUTORS
Route::get('/list-of-national-distributors', 'Frontend\PageController@listOfNationalDistributors')->name('frontend.list-of-national-distributors');

//IFA / RIA CONFERENCES
Route::get('/ifa-ria-conferences', 'Frontend\PageController@ifaRiaConferences')->name('frontend.ifa-ria-conferences');

//WRITE A TESTIMONIAL
Route::get('/write-a-testimonial', 'Frontend\PageController@writeTestimonial')->name('frontend.write-a-testimonial');

//Feedback/Complain
Route::get('/feedback-complain', 'Frontend\PageController@askBrijesh')->name('frontend.ask-brijesh');

//SURVEYS
Route::get('/survey', 'Frontend\SurveyController@index')->name('frontend.survey');

//BUSINESS FAQ
Route::get('/ifa-business-faqs', 'Frontend\BusinessfaqController@index')->name('frontend.ifa-business-faqs');
Route::get('/ifa-business-faqs/{slug}', 'Frontend\BusinessfaqController@category');
Route::get('/ifa-business-faqs/view/{id}', 'Frontend\BusinessfaqController@views');
Route::get('/ifa-business-faqs/like/{id}', 'Frontend\BusinessfaqController@likes');
Route::get('/ifa-business-faqs/details/{slug}', 'Frontend\BusinessfaqController@details')->name('frontend.ifa-business-faqs.details');

//Product FAQ
Route::get('/ifa-product-faqs', 'Frontend\ProductfaqController@index')->name('frontend.ifa-product-faqs');
Route::get('/ifa-product-faqs/{slug}', 'Frontend\ProductfaqController@category');
Route::get('/ifa-product-faqs/view/{id}', 'Frontend\ProductfaqController@views');
Route::get('/ifa-product-faqs/like/{id}', 'Frontend\ProductfaqController@likes');
Route::get('/ifa-product-faqs/details/{slug}', 'Frontend\ProductfaqController@details')->name('frontend.ifa-product-faqs.details');

// ACL

Route::get('/register', 'UserController@create')->name('frontend.register');
Route::get('/register-step2', 'UserController@register_step2')->name('frontend.register_step2');
Route::get('/register-step3', 'UserController@register_step3')->name('frontend.register_step3');
Route::post('/register-save', 'UserController@register_save')->name('frontend.register_save');
// Route::post('/register-save', 'UserController@store')->name('users.store');
Route::get('/free-register', 'UserController@free_register')->name('frontend.free_register');
Route::post('/logo-crop', 'UserController@logoupload')->name('users.logoupload');

Route::get('/forgot-password', 'UserController@forgotPassword')->name('frontend.forgotPassword');
Route::get('/reset-password/{token}', 'UserController@resetPassword')->name('frontend.resetPassword');
Route::post('/reset-password-send', 'UserController@updateResetPassword')->name('frontend.updateResetPassword');
Route::post('/forgot-password-send', 'UserController@updateForgotPassword')->name('frontend.updateForgotPassword');

Route::group(['middleware' => 'verifyMembership'], function () {
    Route::get('/account/profile','Frontend\AccountController@profile')->name('account.profile');
});

Route::get('/account/profile','Frontend\AccountController@profile')->name('account.profile');
Route::get('/account/update-gst-number','Frontend\AccountController@update_gst_number')->name('account.update_gst_number');
Route::post('/account/profile-update/{id}','Frontend\AccountController@profileUpdate')->name('account.profile.update');
Route::post('/account/profile-updatelogo/{id}','Frontend\AccountController@profileUpdate')->name('account.profile.updatelogo');
Route::get('/account/profile-remove-logo/{id}','Frontend\AccountController@profileRemoveLogo')->name('account.profile.remove-logo');

Route::get('/account/cover-image-remove/{id}','Frontend\AccountController@cover_image_remove')->name('account.cover-image-remove');
Route::post('/image-crop', 'Frontend\AccountController@imageUpload');

Route::get('/account/display-settings','Frontend\AccountController@display_settings')->name('account.display-settings');
Route::post('/account/display-settings-update/{id}','Frontend\AccountController@displaysettingsUpdate')->name('account.display-settings.update');

Route::get('/account/user-management', 'Frontend\AccountController@user_management')->name('account.user_management');
Route::get('/account/add-user-management', 'Frontend\AccountController@add_user_management')->name('account.add_user_management');
Route::post('/account/save-user-management', 'Frontend\AccountController@save_user_management')->name('account.save_user_management');
Route::get('/account/edit-user-management/{id}', 'Frontend\AccountController@edit_user_management')->name('account.edit_user_management');
Route::get('/account/resend-user-management/{id}', 'Frontend\AccountController@resend_user_management')->name('account.resend_user_management');
Route::post('/account/update-user-management', 'Frontend\AccountController@update_user_management')->name('account.update_user_management');
Route::post('/account/save-user-permission', 'Frontend\AccountController@save_user_permission')->name('account.save_user_permission');
Route::get('/account/delete-user-management/{id}', 'Frontend\AccountController@delete_user_management')->name('account.delete_user_management');
Route::get('/account/block-user-management/{id}', 'Frontend\AccountController@block_user_management')->name('account.block_user_management');
Route::get('/account/subscription', 'Frontend\AccountController@subscription')->name('account.subscription.index');
Route::get('/account/change-password', 'ChangePasswordController@index')->name('change.password.index');
Route::get('/account/user-invoice', 'Frontend\AccountController@user_invoice')->name('account.user_invoice');
Route::post('/account/change-password', 'ChangePasswordController@store')->name('change.password');
Route::get('/account/subscription/{id}','Frontend\AccountController@subscription_view')->name('account.subscription.view');
Route::get('/account/subscription_add','Frontend\AccountController@subscription_add')->name('account.subscription.add');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');
Route::get('account/subscription-cart', 'Frontend\AccountController@subscription_cart')->name('frontend.membershipcart');
Route::post('account/subscription-renewal', 'Frontend\AccountController@membership_renewal_payment')->name('frontend.membership_renewal_payment');
Route::get('/account/order-list', 'Frontend\AccountController@orderList')->name('account.orderlist.index');
Route::get('/account/order-invioce-download/{id}', 'Frontend\AccountController@order_invioce_download')->name('account.order_invioce_download');
Route::get('/account/order/{id}', 'Frontend\AccountController@orderDetails')->name('account.order.details');
Route::get('/account/refer_to_a_friend', 'Frontend\AccountController@refer_to_a_friend')->name('account.refer_to_a_friend');
Route::get('/account/billing', 'Frontend\AccountController@billing')->name('account.billing');
Route::post('/account/save-billing', 'Frontend\AccountController@save_billing')->name('account.save_billing');
Route::get('/account/download-invoice/{subscription_id}', 'Frontend\AccountController@downloadInvoice')->name('account.downloadInvoice');
Route::get('/account/update-package', 'Frontend\AccountController@upgradePackage')->name('account.upgradePackage');
Route::get('/account/upgrade_number_of_user', 'Frontend\AccountController@upgrade_number_of_user')->name('account.upgrade_number_of_user');
Route::post('/account/upgrade_number_of_user', 'Frontend\AccountController@upgrade_number_of_user')->name('account.upgrade_number_of_user');
Route::post('/account/membership-update-user-callback', 'Frontend\AccountController@membership_update_user_callback')->name('account.membership_update_user_callback');
Route::post('/account/membership-update-user', 'Frontend\AccountController@membership_update_user')->name('account.membership_update_user');

Route::get('/account/my-purchases', 'Frontend\MyPurchasesController@index')->name('account.my_purchases');



Route::get('/premium-banners', 'Frontend\PremiumbannerController@index')->name('frontend.premiumbanner.index');

Route::get('/premium-banners-test', 'Frontend\PremiumbannerController@index_test')->name('frontend.premiumbanner.index_test');

Route::get('/premium-banners/category/{slug}', 'Frontend\PremiumbannerController@category')->name('frontend.premiumbanner.category');
Route::get('/premium-banners/details/{slug}', 'Frontend\PremiumbannerController@details')->name('frontend.premiumbanner.details');
Route::get('/marketing-video', 'Frontend\MarketingvideoController@index')->name('frontend.marketingvideo.index');
Route::get('/marketing-video/{slug}', 'Frontend\MarketingvideoController@details')->name('frontend.marketingvideo.details');
Route::get('/marketing-video/category/{slug}', 'Frontend\MarketingvideoController@category')->name('frontend.marketingvideo.category');

// How to use videos

Route::get('/how-to-use-videos', 'Frontend\VideoController@how_to_use_videos')->name('frontend.how-to-use-videos');
Route::get('/how-to-use-videos/{slug}', 'Frontend\VideoController@how_to_use_videos_details')->name('frontend.how-to-use-videos-details');

// Shouvik PDF
    Route::get('/sales-presenters/premade-sales-presenter', 'Frontend\SalesPresenterPdfController@index')->name('frontend.premade-sales-presenter');
    Route::get('/sales-presenters/premade-sales-presenter-detail/{id}', 'Frontend\SalesPresenterPdfController@detail')->name('frontend.premade-sales-presenter-detail');
    Route::get('/sales-presenters/make-my-pdf/{id}', 'Frontend\SalesPresenterPdfController@make_my_pdf')->name('frontend.make-my-pdf');

    Route::get('/sales-presenters/make-my-pdf-land/{id}', 'Frontend\SalesPresenterPdfController@make_my_pdf_land')->name('frontend.make-my-pdf');

    Route::get('/sales-presenters/pdf-smple-portrait/{id}', 'Frontend\SalesPresenterPdfController@pdf_smple_portrait')->name('frontend.pdf-smple-portrait');

    Route::get('/sales-presenters/pdf-smple-landscape/{id}', 'Frontend\SalesPresenterPdfController@pdf_smple_landscape')->name('frontend.pdf-smple-landscape');

//Sales Presenters
Route::group(['middleware' => 'verifyMembership'], function () {

    Route::get('watermark-image/{image}', 'WaterMarkController@imageWatermark');
    Route::get('watermark-image-sales-presenters/{image}', 'WaterMarkController@imageWatermarkSalesPresenters_test')->name('watermark-image-sales-presenters');
    Route::get('watermark-image-new/{image}', 'WaterMarkController@imageWatermark_new');
    Route::get('watermark-image-test/{image}/{count}', 'WaterMarkController@imageWatermark_test');
    Route::get('watermark-image-demo/{image}', 'WaterMarkController@imageWatermark_test');
    
    // Marketing Video

    Route::get('marketingvideo-download/{slug}/{video}', 'VideomakingController@video_making')->middleware(['verifyMembership']);
    Route::get('marketingvideo-download-new/{slug}/{video}', 'VideomakingController@video_making_new')->middleware(['verifyMembership']);

    Route::get('/sales-presenters/sales-presenters-soft-copy', 'Frontend\SalespresenterController@index')->name('frontend.salespresenters.softcopy.index');
    Route::post('/sales-presenters/savelist', 'Frontend\SalespresenterController@saveListData')->name('frontend.salespresenters.saveListData');
    Route::get('/sales-presenters/my-saved-list', 'Frontend\SalespresenterController@mySaveList')->name('frontend.salespresenters.mysavelist.index');
    Route::get('/sales-presenters/my-saved-list-delete/{slug}', 'Frontend\SalespresenterController@deleteList')->name('frontend.salespresenters.deletelist');
    Route::get('/sales-presenters/sales-presenters-soft-copy-edit/{slug}', 'Frontend\SalespresenterController@editsavelist')->name('frontend.salespresenters.mysavelist.editsavelist');
    Route::post('/sales-presenters/updatelist', 'Frontend\SalespresenterController@updateListData')->name('frontend.salespresenters.updateListData');
    Route::get('/sales-presenters/arrange-save-list/{slug}', 'Frontend\SalespresenterController@arrangeSaveList')->name('frontend.salespresenters.mysavelist.arrange-save-list');
    Route::post('/sales-presenters/update-position', 'Frontend\SalespresenterController@updatePosition')->name('frontend.salespresenters.updatePosition');
    Route::get('/sales-presenters/show-suggested/{slug}', 'Frontend\SalespresenterController@show_suggested')->name('frontend.salespresenters.mysavelist.show_suggested');
    Route::get('/sales-presenters/download-pdf-wcp/{slug}/{type}', 'Frontend\SalespresenterController@download_pdf_without_cover')->name('frontend.salespresenters.mysavelist.download_pdf_without_cover');
    Route::get('/sales-presenters/download-pdf/{slug}', 'Frontend\SalespresenterController@download_pdf')->name('frontend.salespresenters.mysavelist.download_pdf');
    Route::get('/sales-presenters/download-pdf-post', 'Frontend\SalespresenterController@download_pdf_post')->name('frontend.salespresenters.mysavelist.download_pdf_post');

    Route::get('checklist/{list_name}', 'Frontend\SalespresenterController@checkSavelist');

    Route::get('/sales-presenters/how-to-use-sales-presenters', 'Frontend\PageController@howToUseSalesPresenters')->name('frontend.salespresenters.howToUseSalesPresenters');
});
Route::get('/sales-presenters/sales-presenters-soft-copy/check-sample', 'Frontend\SalespresenterController@check_sample')->name('frontend.salespresenters.softcopy.check-sample');
//Stationary

Route::get('/store', 'Frontend\StationaryController@index')->name('frontend.stationary');
Route::get('/store/{slug}', 'Frontend\StationaryController@details')->name('frontend.stationary-details');
Route::get('/store-pdf/{slug}', 'Frontend\StationaryController@viewPdf')->name('frontend.stationary-pdf-details');
Route::get('cart', 'Frontend\StationaryController@cart')->name('frontend.cart');
Route::get('add-to-cart/{id}', 'Frontend\StationaryController@addToCart');
Route::post('add-to-cart-from-details', 'Frontend\StationaryController@addToCartFromDetails')->name('stationary.cart.addToCartFromDetails');
Route::get('cart/remove/{id}', 'Frontend\StationaryController@removeFromCart');
Route::post('/cart/update', 'Frontend\StationaryController@updateCart')->name('stationary.cart.update');
Route::get('process-to-checkout/', 'Frontend\StationaryController@processToCheckout')->name('frontend.stationary.process-to-checkout')->middleware('auth');
Route::post('process-to-checkout/payment', 'Frontend\StationaryController@payment')->name('frontend.stationary.payment');
Route::get('product-purchase/success/{order_id}', 'Frontend\StationaryController@thankyou')->name('frontend.stationary.purchase.success');
Route::get('coupon/{coupon_code}', 'Frontend\StationaryController@couponverify');
Route::get('check-point/{value}', 'Frontend\StationaryController@checkPoint');

Route::get('/store/package-details/{slug}', 'Frontend\StationaryController@package_details')->name('frontend.store.package-details');

//Pre made sales presenters

Route::get('/premade-sales-presenters', 'Frontend\StationaryController@premadesalespresenters')->name('frontend.premade-sales-presenters');
Route::get('/premade-sales-presenters/category/{slug}', 'Frontend\StationaryController@premadesalespresenters_products')->name('frontend.premade-sales-presenters-products');

// Sample Reports

Route::get('how-to-use-calculator', 'Frontend\PageController@how_to_use_calculator')->name('frontend.how-to-use-calculator');
Route::get('samplereports', 'Frontend\PageController@samplereports')->name('frontend.samplereports');
Route::get('samplereports-view/{pdf_name}', 'Frontend\PageController@samplereports_view')->name('frontend.samplereports_view');
Route::get('samplepdf', 'Frontend\PageController@samplepdf')->name('frontend.samplepdf');

Route::get('/investment-suitability-profiler', 'Frontend\AssetAllocationQuestionController@index')->name('frontend.asset-allocation-exam')->middleware(['verifyMembership']);
Route::get('/suitability-profiler-download-saved-file/{id}', 'Frontend\AssetAllocationQuestionController@suitability_profiler_download_saved')->name('frontend.suitability_profiler_download_saved')->middleware(['verifyMembership']);
Route::get('/investment-suitability-profiler-ajax', 'Frontend\AssetAllocationQuestionController@ajax')->name('frontend.asset-allocation-exam-ajax');
Route::get('/investment-suitability-profiler-get-options-product-ajax', 'Frontend\AssetAllocationQuestionController@ajax_get_options')->name('frontend.asset-allocation-exam-get-options-product-ajax');
Route::post('/investment-suitability-profiler/output', 'Frontend\AssetAllocationQuestionController@output')->name('frontend.asset-allocation-exam-output');
Route::get('/investment-suitability-profiler/pdf', 'Frontend\AssetAllocationQuestionController@pdf_download')->name('frontend.asset-allocation-exam-pdf');
Route::get('/investment-suitability-profiler/pdf/{type}', 'Frontend\AssetAllocationQuestionController@pdf_download_type')->name('frontend.asset-allocation-exam-pdf-type');

Route::post('/investment-suitability-profiler-ajax-image', 'Frontend\AssetAllocationQuestionController@ajax_image')->name('frontend.asset-allocation-exam-ajax-image');
Route::post('/investment-suitability-profiler-ajax-image2', 'Frontend\AssetAllocationQuestionController@ajax_image2')->name('frontend.asset-allocation-exam-ajax-image2');

Route::get('/investment-suitability-profiler/save', 'Frontend\AssetAllocationQuestionController@save_data_pdf')->name('frontend.asset-allocation-exam-save');
Route::get('/investment-suitability-profiler/view-saved-files', 'Frontend\AssetAllocationQuestionController@view_saved_files')->name('frontend.view_saved_asset')->middleware(['verifyMembership']);
Route::get('/investment-suitability-profiler/edit-saved-files/{id}', 'Frontend\AssetAllocationQuestionController@edit_saved_files')->name('frontend.edit_saved_files')->middleware(['verifyMembership']);
Route::get('/investment-suitability-profiler/download-questiondetails', 'Frontend\AssetAllocationQuestionController@download_questiondetails')->name('frontend.asset-allocation-download-questiondetails');

// Webinar

Route::get('webinars', 'Frontend\WebinarController@index')->name('frontend.webinars');
Route::get('webinars/{slug}', 'Frontend\WebinarController@details')->name('frontend.webinars.details');

//Share 


Route::get('whatappshare/{premiumimage}', 'ShareController@whatsappshare')->name('frontend.banner.share.whatsapp');
Route::get('salespresenters/whatappshare/{softcopy}', 'ShareController@whatsappshare_softcopy')->name('frontend.softcopy.share.whatsapp');


//Calculators
Route::get('/calculators/view-saved-files', 'Frontend\CalculatorController@view_saved_files')->name('frontend.view_saved_files')->middleware(['verifyMembership']);
Route::post('/calculators/line-chart', 'Frontend\CalculatorController@line_chart')->name('frontend.calculators_line_chart')->middleware(['verifyMembership']);

Route::get('/calculators/view-saved-file-details/{id}', 'Frontend\CalculatorController@view_saved_file_details')->name('frontend.view_saved_file_details')->middleware(['verifyMembership']);
Route::get('/calculators/remove-saved-files', 'Frontend\CalculatorController@remove_saved_file_details')->name('frontend.remove_saved_file_details')->middleware(['verifyMembership']);
Route::get('/calculators/download-saved-file-details/{id}', 'Frontend\CalculatorController@download_saved_file_details')->name('frontend.download_saved_file_details')->middleware(['verifyMembership']);
Route::get('/calculators/merger-download-saved-file-details', 'Frontend\CalculatorController@merge_sp_download_save_file')->name('frontend.merge_sp_download_save_file')->middleware(['verifyMembership']);

Route::get('/calculators', 'Frontend\CalculatorController@about')->name('frontend.about');

Route::get('/calculators/lump-sum', 'Frontend\CalculatorController@lump_sum_index')->name('frontend.lumpSumIndex');
// Route::get('/calculators/onetime-investment/future-value-of-one-time-investment', 'Frontend\CalculatorController@future_value_of_lumpsum_investment')->name('frontend.futureValueOfLumpsumInvestment')->middleware(['verifyMembership']);
// Route::post('/calculators/onetime-investment/future-value-of-one-time-investment-output', 'Frontend\CalculatorController@future_value_of_lumpsum_investment_output')->name('frontend.futureValueOfLumpsumInvestmentOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/onetime-investment/future-value-of-one-time-investment-output', 'Frontend\CalculatorController@future_value_of_lumpsum_investment_output')->name('frontend.futureValueOfLumpsumInvestmentOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/onetime-investment/future-value-of-one-time-investment-output-pdf', 'Frontend\CalculatorController@future_value_of_lumpsum_investment_output_pdf_download')->name('frontend.futureValueOfLumpsumInvestmentOutputPdf')->middleware(['verifyMembership']);
// Route::get('/calculators/onetime-investment/future-value-of-one-time-investment-output-save', 'Frontend\CalculatorController@future_value_of_lumpsum_investment_output_save')->name('frontend.futureValueOfLumpsumInvestmentOutputSave')->middleware(['verifyMembership']);


// Route::get('/calculators/onetime-investment/one-time-investment-need-based-ready-reckoner', 'Frontend\CalculatorController@one_time_investment_goal_planning_ready_reckoner')->name('frontend.oneTimeInvestmentGoalPlanningReadyReckoner')->middleware(['verifyMembership']);
// Route::post('/calculators/onetime-investment/one-time-investment-need-based-ready-reckoner-output', 'Frontend\CalculatorController@one_time_investment_goal_planning_ready_reckoner_output')->name('frontend.oneTimeInvestmentGoalPlanningReadyReckonerOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/onetime-investment/one-time-investment-need-based-ready-reckoner-output', 'Frontend\CalculatorController@one_time_investment_goal_planning_ready_reckoner_output')->name('frontend.oneTimeInvestmentGoalPlanningReadyReckonerOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/onetime-investment/one-time-investment-need-based-ready-reckoner-output-pdf', 'Frontend\CalculatorController@one_time_investment_goal_planning_ready_reckoner_output_pdf_download')->name('frontend.oneTimeInvestmentGoalPlanningReadyReckonerOutputPdf')->middleware(['verifyMembership']);
// Route::get('/calculators/onetime-investment/one-time-investment-need-based-ready-reckoner-output-save', 'Frontend\CalculatorController@one_time_investment_goal_planning_ready_reckoner_output_save')->name('frontend.oneTimeInvestmentGoalPlanningReadyReckonerOutputSave')->middleware(['verifyMembership']);

//SIP
// Route::get('/calculators/sip-calculator', 'Frontend\SipCalculatorController@index')->name('frontend.sipIndex');
// Route::get('/calculators/sip-calculator/future-value-of-sip', 'Frontend\SipCalculatorController@futureValueOfSipIndex')->name('frontend.futureValueOfSipIndex')->middleware(['verifyMembership']);
// Route::post('/calculators/sip-calculator/future-value-of-sip-output', 'Frontend\SipCalculatorController@futureValueOfSipOutput')->name('frontend.futureValueOfSipOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/future-value-of-sip-output', 'Frontend\SipCalculatorController@futureValueOfSipOutput')->name('frontend.futureValueOfSipOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/future-value-of-sip-output-pdf', 'Frontend\SipCalculatorController@futureValueOfSipOutputPdfDownload')->name('frontend.futureValueOfSipOutputPdfDownload')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/future-value-of-sip-output-save', 'Frontend\SipCalculatorController@futureValueOfSipOutputSave')->name('frontend.futureValueOfSipOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/sip-calculator/sip-required-for-target-future-value', 'Frontend\SipCalculatorController@sipRequiredForFutureValue')->name('frontend.sipRequiredForFutureValue')->middleware(['verifyMembership']);
// Route::post('/calculators/sip-calculator/sip-required-for-target-future-value-output', 'Frontend\SipCalculatorController@sipRequiredForFutureValueOutput')->name('frontend.sipRequiredForFutureValueOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/sip-required-for-target-future-value-output', 'Frontend\SipCalculatorController@sipRequiredForFutureValueOutput')->name('frontend.sipRequiredForFutureValueOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/sip-required-for-target-future-value-output-pdf', 'Frontend\SipCalculatorController@sipRequiredForFutureValueOutputPdfDownload')->name('frontend.sipRequiredForFutureValueOutputPdfDownload')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/sip-required-for-target-future-value-output-save', 'Frontend\SipCalculatorController@sipRequiredForFutureValueOutputSave')->name('frontend.sipRequiredForFutureValueOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/sip-calculator/limited-period-sip-calculator', 'Frontend\SipCalculatorController@limitedPeriodSIPfutureValueAfterDefermentPeriod')->name('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod')->middleware(['verifyMembership']);
// Route::post('/calculators/sip-calculator/limited-period-sip-calculator-output', 'Frontend\SipCalculatorController@limitedPeriodSIPfutureValueAfterDefermentPeriodOutput')->name('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriodOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/limited-period-sip-calculator-output', 'Frontend\SipCalculatorController@limitedPeriodSIPfutureValueAfterDefermentPeriodOutput')->name('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriodOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/limited-period-sip-calculator-output-pdf', 'Frontend\SipCalculatorController@limitedPeriodSIPfutureValueAfterDefermentPeriodOutputPdfDownload')->name('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriodOutputPdfDownload')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/limited-period-sip-calculator-output-save', 'Frontend\SipCalculatorController@limitedPeriodSIPfutureValueAfterDefermentPeriodOutputSave')->name('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriodOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/sip-calculator/limited-period-sip-need-based-calculator', 'Frontend\SipCalculatorController@limitedPeriodSIPgoalPlanningCalculator')->name('frontend.limitedPeriodSIPgoalPlanningCalculator')->middleware(['verifyMembership']);
// Route::post('/calculators/sip-calculator/limited-period-sip-need-based-calculator-output', 'Frontend\SipCalculatorController@limitedPeriodSIPgoalPlanningCalculatorOutput')->name('frontend.limitedPeriodSIPgoalPlanningCalculatorOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/limited-period-sip-need-based-calculator-output', 'Frontend\SipCalculatorController@limitedPeriodSIPgoalPlanningCalculatorOutput')->name('frontend.limitedPeriodSIPgoalPlanningCalculatorOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/limited-period-sip-need-based-calculator-output-pdf', 'Frontend\SipCalculatorController@limitedPeriodSIPgoalPlanningCalculatorOutputPdfDownload')->name('frontend.limitedPeriodSIPgoalPlanningCalculatorOutputPdfDownload')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/limited-period-sip-need-based-calculator-output-save', 'Frontend\SipCalculatorController@limitedPeriodSIPgoalPlanningCalculatorOutputSave')->name('frontend.limitedPeriodSIPgoalPlanningCalculatorOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/sip-calculator/future-value-of-stepup-sip', 'Frontend\SipCalculatorController@futureValueOfStepUpSIP')->name('frontend.futureValueOfStepUpSIP')->middleware(['verifyMembership']);
// Route::post('/calculators/sip-calculator/future-value-of-stepup-sip-output', 'Frontend\SipCalculatorController@futureValueOfStepUpSIPOutput')->name('frontend.futureValueOfStepUpSIPOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-output', 'Frontend\SipCalculatorController@futureValueOfStepUpSIPOutput')->name('frontend.futureValueOfStepUpSIPOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-output-pdf', 'Frontend\SipCalculatorController@futureValueOfStepUpSIPOutputPdfDownload')->name('frontend.futureValueOfStepUpSIPOutputPdfDownload')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-output-save', 'Frontend\SipCalculatorController@futureValueOfStepUpSIPOutputSave')->name('frontend.futureValueOfStepUpSIPOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-required-target', 'Frontend\SipCalculatorController@futureValueOfStepUpSIPRequiredTarget')->name('frontend.futureValueOfStepUpSIPRequiredTarget')->middleware(['verifyMembership']);
// Route::post('/calculators/sip-calculator/future-value-of-stepup-sip-required-target-output', 'Frontend\SipCalculatorController@futureValueOfStepUpSIPRequiredTargetOutput')->name('frontend.futureValueOfStepUpSIPRequiredTargetOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-required-target-output', 'Frontend\SipCalculatorController@futureValueOfStepUpSIPRequiredTargetOutput')->name('frontend.futureValueOfStepUpSIPRequiredTargetOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-required-target-output-pdf', 'Frontend\SipCalculatorController@futureValueOfStepUpSIPRequiredTargetOutputPdfDownload')->name('frontend.futureValueOfStepUpSIPRequiredTargetOutputPdfDownload')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-required-target-output-save', 'Frontend\SipCalculatorController@futureValueOfStepUpSIPRequiredTargetOutputSave')->name('frontend.futureValueOfStepUpSIPRequiredTargetOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/sip-calculator/sip-future-value-ready-recokner', 'Frontend\SipCalculatorController@sipFutureValueReadyRecokner')->name('frontend.sipFutureValueReadyRecokner')->middleware(['verifyMembership']);
// Route::post('/calculators/sip-calculator/sip-future-value-ready-recokner-output', 'Frontend\SipCalculatorController@sipFutureValueReadyRecoknerOutput')->name('frontend.sipFutureValueReadyRecoknerOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/sip-future-value-ready-recokner-output', 'Frontend\SipCalculatorController@sipFutureValueReadyRecoknerOutput')->name('frontend.sipFutureValueReadyRecoknerOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/sip-future-value-ready-recokner-output-pdf', 'Frontend\SipCalculatorController@sipFutureValueReadyRecoknerOutputDownloadPdf')->name('frontend.sipFutureValueReadyRecoknerOutputDownloadPdf')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/sip-future-value-ready-recokner-output-save', 'Frontend\SipCalculatorController@sipFutureValueReadyRecoknerOutputSave')->name('frontend.sipFutureValueReadyRecoknerOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/sip-calculator/sip-need-based-ready-reckoner', 'Frontend\SipCalculatorController@sipGoalPlanningReadyRecokner')->name('frontend.sipGoalPlanningReadyRecokner')->middleware(['verifyMembership']);
// Route::post('/calculators/sip-calculator/sip-need-based-ready-reckoner-output', 'Frontend\SipCalculatorController@sipGoalPlanningReadyRecoknerOutput')->name('frontend.sipGoalPlanningReadyRecoknerOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/sip-need-based-ready-reckoner-output', 'Frontend\SipCalculatorController@sipGoalPlanningReadyRecoknerOutput')->name('frontend.sipGoalPlanningReadyRecoknerOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/sip-need-based-ready-reckoner-output-pdf', 'Frontend\SipCalculatorController@sipGoalPlanningReadyRecoknerOutputDownloadPdf')->name('frontend.sipGoalPlanningReadyRecoknerOutputDownloadPdf')->middleware(['verifyMembership']);
// Route::get('/calculators/sip-calculator/sip-need-based-ready-reckoner-output-save', 'Frontend\SipCalculatorController@sipGoalPlanningReadyRecoknerOutputSave')->name('frontend.sipGoalPlanningReadyRecoknerOutputSave')->middleware(['verifyMembership']);

//STP
Route::get('/calculators/stp-calculator', 'Frontend\StpCalculatorController@index')->name('frontend.stpIndex');
// Route::get('/calculators/stp-calculator/future-value-of-stp', 'Frontend\StpCalculatorController@futureValueOfSTP')->name('frontend.futureValueOfSTP')->middleware(['verifyMembership']);
// Route::post('/calculators/stp-calculator/future-value-of-stp-output', 'Frontend\StpCalculatorController@futureValueOfSTPOutput')->name('frontend.futureValueOfSTPOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/stp-calculator/future-value-of-stp-output', 'Frontend\StpCalculatorController@futureValueOfSTPOutput')->name('frontend.futureValueOfSTPOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/stp-calculator/future-value-of-stp-output-pdf', 'Frontend\StpCalculatorController@futureValueOfSTPOutputDownloadPDF')->name('frontend.futureValueOfSTPOutputDownloadPDF')->middleware(['verifyMembership']);
// Route::get('/calculators/stp-calculator/future-value-of-stp-output-save', 'Frontend\StpCalculatorController@futureValueOfSTPOutputSave')->name('frontend.futureValueOfSTPOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/stp-calculator/stp-required-for-target-future-value', 'Frontend\StpCalculatorController@stpRequiredForTargetFutureValue')->name('frontend.stpRequiredForTargetFutureValue')->middleware(['verifyMembership']);
// Route::post('/calculators/stp-calculator/stp-required-for-target-future-value-output', 'Frontend\StpCalculatorController@stpRequiredForTargetFutureValueOutput')->name('frontend.stpRequiredForTargetFutureValueOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/stp-calculator/stp-required-for-target-future-value-output', 'Frontend\StpCalculatorController@stpRequiredForTargetFutureValueOutput')->name('frontend.stpRequiredForTargetFutureValueOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/stp-calculator/stp-required-for-target-future-value-output-pdf', 'Frontend\StpCalculatorController@stpRequiredForTargetFutureValueOutputDownloadPDF')->name('frontend.stpRequiredForTargetFutureValueOutputDownloadPDF')->middleware(['verifyMembership']);
// Route::get('/calculators/stp-calculator/stp-required-for-target-future-value-output-save', 'Frontend\StpCalculatorController@stpRequiredForTargetFutureValueOutputSave')->name('frontend.stpRequiredForTargetFutureValueOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/stp-calculator/stp-future-value-ready-recokner', 'Frontend\StpCalculatorController@stpFutureValueReadyRecokner')->name('frontend.stpFutureValueReadyRecokner')->middleware(['verifyMembership']);
// Route::post('/calculators/stp-calculator/stp-future-value-ready-recokner-output', 'Frontend\StpCalculatorController@stpFutureValueReadyRecoknerOutput')->name('frontend.stpFutureValueReadyRecoknerOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/stp-calculator/stp-future-value-ready-recokner-output', 'Frontend\StpCalculatorController@stpFutureValueReadyRecoknerOutput')->name('frontend.stpFutureValueReadyRecoknerOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/stp-calculator/stp-future-value-ready-recokner-output-pdf', 'Frontend\StpCalculatorController@stpFutureValueReadyRecoknerOutputDownloadPDF')->name('frontend.stpFutureValueReadyRecoknerOutputDownloadPDF')->middleware(['verifyMembership']);
// Route::get('/calculators/stp-calculator/stp-future-value-ready-recokner-output-save', 'Frontend\StpCalculatorController@stpFutureValueReadyRecoknerOutputSave')->name('frontend.stpFutureValueReadyRecoknerSave')->middleware(['verifyMembership']);

// Route::get('/calculators/stp-calculator/stp-need-based-ready-reckoner', 'Frontend\StpCalculatorController@stpGoalPlanningValueReadyRecokner')->name('frontend.stpGoalPlanningValueReadyRecokner')->middleware(['verifyMembership']);
// Route::post('/calculators/stp-calculator/stp-need-based-ready-reckoner-output', 'Frontend\StpCalculatorController@stpGoalPlanningValueReadyRecoknerOutput')->name('frontend.stpGoalPlanningValueReadyRecoknerOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/stp-calculator/stp-need-based-ready-reckoner-output', 'Frontend\StpCalculatorController@stpGoalPlanningValueReadyRecoknerOutput')->name('frontend.stpGoalPlanningValueReadyRecoknerOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/stp-calculator/stp-need-based-ready-reckoner-output-pdf', 'Frontend\StpCalculatorController@stpGoalPlanningValueReadyRecoknerOutputDownloadPDF')->name('frontend.stpGoalPlanningValueReadyRecoknerOutputDownloadPDF')->middleware(['verifyMembership']);
// Route::get('/calculators/stp-calculator/stp-need-based-ready-reckoner-output-save', 'Frontend\StpCalculatorController@stpGoalPlanningValueReadyRecoknerOutputSave')->name('frontend.stpGoalPlanningValueReadyRecoknerOutputSave')->middleware(['verifyMembership']);

//Other
Route::get('/calculators/other', 'Frontend\OtherCalculatorController@index')->name('frontend.other_calculator')->middleware(['verifyMembership']);

Route::get('/calculators/other/recover-your-emis-through-sips', 'Frontend\OtherCalculatorController@RecoverYourEMIsThroughSIPs')->name('frontend.RecoverYourEMIsThroughSIPs')->middleware(['verifyMembership']);
Route::post('/calculators/other/recover-your-emis-through-sips-output', 'Frontend\OtherCalculatorController@RecoverYourEMIsThroughSIPs_output')->name('frontend.RecoverYourEMIsThroughSIPs_output')->middleware(['verifyMembership']);
Route::get('/calculators/other/recover-your-emis-through-sips-pdf', 'Frontend\OtherCalculatorController@RecoverYourEMIsThroughSIPs_output_pdf')->name('frontend.RecoverYourEMIsThroughSIPs_output_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/other/recover-your-emis-through-sips-save', 'Frontend\OtherCalculatorController@RecoverYourEMIsThroughSIPs_output_save')->name('frontend.RecoverYourEMIsThroughSIPs_output_save')->middleware(['verifyMembership']);

Route::get('/calculators/other/bank-fixed-deposit-vs-debt-fund', 'Frontend\OtherCalculatorController@bank_fixed_deposit_vs_debt_fund')->name('frontend.bank_fixed_deposit_vs_debt_fund')->middleware(['verifyMembership']);
Route::post('/calculators/other/bank-fixed-deposit-vs-debt-fund-output', 'Frontend\OtherCalculatorController@bank_fixed_deposit_vs_debt_fund_output')->name('frontend.bank_fixed_deposit_vs_debt_fund_output')->middleware(['verifyMembership']);
Route::get('/calculators/other/bank-fixed-deposit-vs-debt-fund-pdf', 'Frontend\OtherCalculatorController@bank_fixed_deposit_vs_debt_fund_pdf')->name('frontend.bank_fixed_deposit_vs_debt_fund_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/other/bank-fixed-deposit-vs-debt-fund-save', 'Frontend\OtherCalculatorController@bank_fixed_deposit_vs_debt_fund_save')->name('frontend.bank_fixed_deposit_vs_debt_fund_save')->middleware(['verifyMembership']);

Route::get('/calculators/other/cg-bond-vs-other-investment', 'Frontend\OtherCalculatorController@CG_Bond_vs_Other_Investment')->name('frontend.CG_Bond_vs_Other_Investment')->middleware(['verifyMembership']);
Route::post('/calculators/other/cg-bond-vs-other-investment-output', 'Frontend\OtherCalculatorController@CG_Bond_vs_Other_Investment_output')->name('frontend.CG_Bond_vs_Other_Investment_output')->middleware(['verifyMembership']);
Route::get('/calculators/other/cg-bond-vs-other-investment-pdf', 'Frontend\OtherCalculatorController@CG_Bond_vs_Other_Investment_pdf')->name('frontend.CG_Bond_vs_Other_Investment_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/other/cg-bond-vs-other-investment-save', 'Frontend\OtherCalculatorController@CG_Bond_vs_Other_Investment_save')->name('frontend.CG_Bond_vs_Other_Investment_save')->middleware(['verifyMembership']);




//SWP
Route::get('/calculators/swp-calculator', 'Frontend\SwpCalculatorController@index')->name('frontend.swpIndex');

Route::get('/calculators/swp-calculator/bank-fd-vs-mutual-fund-swp', 'Frontend\SwpCalculatorController@bank_fd_vs_mutual_fund_swp')->name('frontend.bank_fd_vs_mutual_fund_swp')->middleware(['verifyMembership']);
Route::post('/calculators/swp-calculator/bank-fd-vs-mutual-fund-swp-output', 'Frontend\SwpCalculatorController@bank_fd_vs_mutual_fund_swp_output')->name('frontend.bank_fd_vs_mutual_fund_swp_output')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/bank-fd-vs-mutual-fund-swp-pdf', 'Frontend\SwpCalculatorController@bank_fd_vs_mutual_fund_swp_pdf')->name('frontend.bank_fd_vs_mutual_fund_swp_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/bank-fd-vs-mutual-fund-swp-save', 'Frontend\SwpCalculatorController@bank_fd_vs_mutual_fund_swp_save')->name('frontend.bank_fd_vs_mutual_fund_swp_save')->middleware(['verifyMembership']);

// Route::get('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment', 'Frontend\SwpCalculatorController@monthlyAnnuityForLumpsumInvestment')->name('frontend.monthlyAnnuityForLumpsumInvestment')->middleware(['verifyMembership']);
// Route::post('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment-output', 'Frontend\SwpCalculatorController@monthlyAnnuityForLumpsumInvestmentOutput')->name('frontend.monthlyAnnuityForLumpsumInvestmentOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment-output', 'Frontend\SwpCalculatorController@monthlyAnnuityForLumpsumInvestmentOutput')->name('frontend.monthlyAnnuityForLumpsumInvestmentOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment-output-pdf', 'Frontend\SwpCalculatorController@monthlyAnnuityForLumpsumInvestmentOutputDownloadPDF')->name('frontend.monthlyAnnuityForLumpsumInvestmentOutputDownloadPDF')->middleware(['verifyMembership']);
// Route::get('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment-output-save', 'Frontend\SwpCalculatorController@monthlyAnnuityForLumpsumInvestmentOutputSave')->name('frontend.monthlyAnnuityForLumpsumInvestmentOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity', 'Frontend\SwpCalculatorController@lumpsumInvestmentRequiredForTargetMonthlyAnnuity')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuity')->middleware(['verifyMembership']);
// Route::post('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-output', 'Frontend\SwpCalculatorController@lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutput')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-output', 'Frontend\SwpCalculatorController@lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutput')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-output-pdf', 'Frontend\SwpCalculatorController@lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputDownloadPdf')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputDownloadPdf')->middleware(['verifyMembership']);
// Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-output-save', 'Frontend\SwpCalculatorController@lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputSave')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputSave')->middleware(['verifyMembership']);


Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-old', 'Frontend\SwpCalculatorController@OldlumpsumInvestmentRequiredForTargetMonthlyAnnuity')->name('frontend.OldlumpsumInvestmentRequiredForTargetMonthlyAnnuity')->middleware(['verifyMembership']);
Route::post('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-output-old', 'Frontend\SwpCalculatorController@OldlumpsumInvestmentRequiredForTargetMonthlyAnnuityOutput')->name('frontend.OldlumpsumInvestmentRequiredForTargetMonthlyAnnuityOutput')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-output-old', 'Frontend\SwpCalculatorController@OldlumpsumInvestmentRequiredForTargetMonthlyAnnuityOutput')->name('frontend.OldlumpsumInvestmentRequiredForTargetMonthlyAnnuityOutput')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-output-pdf-old', 'Frontend\SwpCalculatorController@OldlumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputDownloadPdf')->name('frontend.OldlumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputDownloadPdf')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-output-save-old', 'Frontend\SwpCalculatorController@OldlumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputSave')->name('frontend.OldlumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputSave')->middleware(['verifyMembership']);

Route::get('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment-with-deferment-period', 'Frontend\SwpCalculatorController@monthlyAnnuityForlumpsumInvestmentWithDefermentPeriod')->name('frontend.monthlyAnnuityForlumpsumInvestmentWithDefermentPeriod')->middleware(['verifyMembership']);
Route::post('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment-with-deferment-period-output', 'Frontend\SwpCalculatorController@monthlyAnnuityForlumpsumInvestmentWithDefermentPeriodOutput')->name('frontend.monthlyAnnuityForlumpsumInvestmentWithDefermentPeriodOutput')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment-with-deferment-period-output', 'Frontend\SwpCalculatorController@monthlyAnnuityForlumpsumInvestmentWithDefermentPeriodOutput')->name('frontend.monthlyAnnuityForlumpsumInvestmentWithDefermentPeriodOutput')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment-with-deferment-period-output-pdf', 'Frontend\SwpCalculatorController@monthlyAnnuityForlumpsumInvestmentWithDefermentPeriodOutputDownloadPdf')->name('frontend.monthlyAnnuityForlumpsumInvestmentWithDefermentPeriodOutputDownloadPdf')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment-with-deferment-period-output-save', 'Frontend\SwpCalculatorController@monthlyAnnuityForlumpsumInvestmentWithDefermentPeriodOutputSave')->name('frontend.monthlyAnnuityForlumpsumInvestmentWithDefermentPeriodOutputSave')->middleware(['verifyMembership']);

Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-with-deferment', 'Frontend\SwpCalculatorController@lumpsumInvestmentRequiredForTargetMonthlyAnnuityWithDeferment')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityWithDeferment')->middleware(['verifyMembership']);
Route::post('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-with-deferment-output', 'Frontend\SwpCalculatorController@lumpsumInvestmentRequiredForTargetMonthlyAnnuityWithDefermentOutput')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityWithDefermentOutput')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-with-deferment-output', 'Frontend\SwpCalculatorController@lumpsumInvestmentRequiredForTargetMonthlyAnnuityWithDefermentOutput')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityWithDefermentOutput')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-with-deferment-output-pdf', 'Frontend\SwpCalculatorController@lumpsumInvestmentRequiredForTargetMonthlyAnnuityWithDefermentOutputDownloadPdf')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityWithDefermentOutputDownloadPdf')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-with-deferment-output-save', 'Frontend\SwpCalculatorController@lumpsumInvestmentRequiredForTargetMonthlyAnnuityWithDefermentOutputSave')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityWithDefermentOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/swp-calculator/monthly-annuity-for-sip', 'Frontend\SwpCalculatorController@monthlyAnnuityForSIP')->name('frontend.monthlyAnnuityForSIP')->middleware(['verifyMembership']);
// Route::get('/calculators/swp-calculator/monthly-annuity-for-sip-output', 'Frontend\SwpCalculatorController@monthlyAnnuityForSIPOUTPUT')->name('frontend.monthlyAnnuityForSIPOUTPUT')->middleware(['verifyMembership']);
// Route::post('/calculators/swp-calculator/monthly-annuity-for-sip-output', 'Frontend\SwpCalculatorController@monthlyAnnuityForSIPOUTPUT')->name('frontend.monthlyAnnuityForSIPOUTPUT')->middleware(['verifyMembership']);
// Route::get('/calculators/swp-calculator/monthly-annuity-for-sip-output-pdf', 'Frontend\SwpCalculatorController@monthlyAnnuityForSIPOutputDownloadPdf')->name('frontend.monthlyAnnuityForSIPOutputDownloadPdf')->middleware(['verifyMembership']);
// Route::get('/calculators/swp-calculator/monthly-annuity-for-sip-output-save', 'Frontend\SwpCalculatorController@monthlyAnnuityForSIPOutputSave')->name('frontend.monthlyAnnuityForSIPOutputSave')->middleware(['verifyMembership']);

Route::get('/calculators/swp-calculator/monthly-annuity-for-sip-old', 'Frontend\SwpCalculatorController@OldmonthlyAnnuityForSIP')->name('frontend.OldmonthlyAnnuityForSIP')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-sip-output-old', 'Frontend\SwpCalculatorController@OldmonthlyAnnuityForSIPOUTPUT')->name('frontend.OldmonthlyAnnuityForSIPOUTPUT')->middleware(['verifyMembership']);
Route::post('/calculators/swp-calculator/monthly-annuity-for-sip-output-old', 'Frontend\SwpCalculatorController@OldmonthlyAnnuityForSIPOUTPUT')->name('frontend.OldmonthlyAnnuityForSIPOUTPUT')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-sip-output-pdf-old', 'Frontend\SwpCalculatorController@OldmonthlyAnnuityForSIPOutputDownloadPdf')->name('frontend.OldmonthlyAnnuityForSIPOutputDownloadPdf')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-sip-output-save-old', 'Frontend\SwpCalculatorController@OldmonthlyAnnuityForSIPOutputSave')->name('frontend.OldmonthlyAnnuityForSIPOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/swp-calculator/sip-required-for-target-monthly-annuity', 'Frontend\SwpCalculatorController@sipRequiredForTargetMonthlyAnnuity')->name('frontend.sipRequiredForTargetMonthlyAnnuity')->middleware(['verifyMembership']);
// Route::get('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-output', 'Frontend\SwpCalculatorController@sipRequiredForTargetMonthlyAnnuityOutput')->name('frontend.sipRequiredForTargetMonthlyAnnuityOutput')->middleware(['verifyMembership']);
// Route::post('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-output', 'Frontend\SwpCalculatorController@sipRequiredForTargetMonthlyAnnuityOutput')->name('frontend.sipRequiredForTargetMonthlyAnnuityOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-output-pdf', 'Frontend\SwpCalculatorController@sipRequiredForTargetMonthlyAnnuityOutputDownloadPdf')->name('frontend.sipRequiredForTargetMonthlyAnnuityOutputDownloadPdf')->middleware(['verifyMembership']);
// Route::get('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-output-save', 'Frontend\SwpCalculatorController@sipRequiredForTargetMonthlyAnnuityOutputSave')->name('frontend.sipRequiredForTargetMonthlyAnnuityOutputSave')->middleware(['verifyMembership']);

Route::get('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-old', 'Frontend\SwpCalculatorController@OldsipRequiredForTargetMonthlyAnnuity')->name('frontend.OldsipRequiredForTargetMonthlyAnnuity')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-output-old', 'Frontend\SwpCalculatorController@OldsipRequiredForTargetMonthlyAnnuityOutput')->name('frontend.OldsipRequiredForTargetMonthlyAnnuityOutput')->middleware(['verifyMembership']);
Route::post('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-output-old', 'Frontend\SwpCalculatorController@OldsipRequiredForTargetMonthlyAnnuityOutput')->name('frontend.OldsipRequiredForTargetMonthlyAnnuityOutput')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-output-pdf-old', 'Frontend\SwpCalculatorController@OldsipRequiredForTargetMonthlyAnnuityOutputDownloadPdf')->name('frontend.OldsipRequiredForTargetMonthlyAnnuityOutputDownloadPdf')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-output-save-old', 'Frontend\SwpCalculatorController@OldsipRequiredForTargetMonthlyAnnuityOutputSave')->name('frontend.OldsipRequiredForTargetMonthlyAnnuityOutputSave')->middleware(['verifyMembership']);

//Goal Planning
Route::get('/calculators/goal-planning-calculator', 'Frontend\GoalPlanningCalculatorController@index')->name('frontend.goalplanningIndex');

// Route::get('/calculators/need-based-calculator/child-education-need-based-calculator', 'Frontend\GoalPlanningCalculatorController@childEducation')->name('frontend.childEducation')->middleware(['verifyMembership']);
// Route::post('/calculators/need-based-calculator/child-education-need-based-calculator-output', 'Frontend\GoalPlanningCalculatorController@childEducationOutput')->name('frontend.childEducationOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/need-based-calculator/child-education-need-based-calculator-output', 'Frontend\GoalPlanningCalculatorController@childEducationOutput')->name('frontend.childEducationOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/need-based-calculator/child-education-need-based-calculator-output-pdf', 'Frontend\GoalPlanningCalculatorController@childEducationOutputDownloadPdf')->name('frontend.childEducationOutputDownloadPdf')->middleware(['verifyMembership']);
// Route::get('/calculators/need-based-calculator/child-education-need-based-calculator-output-save', 'Frontend\GoalPlanningCalculatorController@childEducationOutputSave')->name('frontend.childEducationOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/need-based-calculator/retirement-need-based-calculator', 'Frontend\GoalPlanningCalculatorController@retirementPlanning')->name('frontend.retirementPlanning')->middleware(['verifyMembership']);
// Route::get('/calculators/need-based-calculator/retirement-need-based-calculator-output', 'Frontend\GoalPlanningCalculatorController@retirementPlanningOutput')->name('frontend.retirementPlanningOutput')->middleware(['verifyMembership']);
// Route::post('/calculators/need-based-calculator/retirement-need-based-calculator-output', 'Frontend\GoalPlanningCalculatorController@retirementPlanningOutput')->name('frontend.retirementPlanningOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/need-based-calculator/retirement-need-based-calculator-output-pdf', 'Frontend\GoalPlanningCalculatorController@retirementPlanningOutputDownloadPdf')->name('frontend.retirementPlanningOutputDownloadPdf')->middleware(['verifyMembership']);
// Route::get('/calculators/need-based-calculator/retirement-need-based-calculator-output-save', 'Frontend\GoalPlanningCalculatorController@retirementPlanningOutputSave')->name('frontend.retirementPlanningOutputSave')->middleware(['verifyMembership']);

//Insurance
Route::get('/calculators/mf-vs-insurance', 'Frontend\InsuranceCalculatorController@index')->name('frontend.insuranceIndex');

// Route::get('/calculators/mf-vs-insurance/term-insurance-sip', 'Frontend\InsuranceCalculatorController@termInsuranceSIP')->name('frontend.termInsuranceSIP')->middleware(['verifyMembership']);
// Route::post('/calculators/mf-vs-insurance/term-insurance-sip-output', 'Frontend\InsuranceCalculatorController@termInsuranceSIPOutput')->name('frontend.termInsuranceSIPOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/mf-vs-insurance/term-insurance-sip-output', 'Frontend\InsuranceCalculatorController@termInsuranceSIPOutput')->name('frontend.termInsuranceSIPOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/mf-vs-insurance/term-insurance-sip-output-pdf', 'Frontend\InsuranceCalculatorController@termInsuranceSIPOutputPdfDownload')->name('frontend.termInsuranceSIPOutputPdfDownload')->middleware(['verifyMembership']);
// Route::get('/calculators/mf-vs-insurance/term-insurance-sip-output-save', 'Frontend\InsuranceCalculatorController@termInsuranceSIPOutputSave')->name('frontend.termInsuranceSIPOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/mf-vs-insurance/term-insurance-sip-goal-base', 'Frontend\InsuranceCalculatorController@termInsuranceSIPgoalBase')->name('frontend.termInsuranceSIPgoalBase')->middleware(['verifyMembership']);
// Route::get('/calculators/mf-vs-insurance/term-insurance-sip-goal-base-output', 'Frontend\InsuranceCalculatorController@termInsuranceSIPgoalBaseOutput')->name('frontend.termInsuranceSIPgoalBaseOutput')->middleware(['verifyMembership']);
// Route::post('/calculators/mf-vs-insurance/term-insurance-sip-goal-base-output', 'Frontend\InsuranceCalculatorController@termInsuranceSIPgoalBaseOutput')->name('frontend.termInsuranceSIPgoalBaseOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/mf-vs-insurance/term-insurance-sip-goal-base-output-pdf', 'Frontend\InsuranceCalculatorController@termInsuranceSIPgoalBaseOutputPdfDownload')->name('frontend.termInsuranceSIPgoalBaseOutputPdfDownload')->middleware(['verifyMembership']);
// Route::get('/calculators/mf-vs-insurance/term-insurance-sip-goal-base-output-save', 'Frontend\InsuranceCalculatorController@termInsuranceSIPgoalBaseOutputSave')->name('frontend.termInsuranceSIPgoalBaseOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/mf-vs-insurance/insurance-term-cover', 'Frontend\InsuranceCalculatorController@insuranceTermCover')->name('frontend.insuranceTermCover')->middleware(['verifyMembership']);
// Route::get('/calculators/mf-vs-insurance/insurance-term-cover-output', 'Frontend\InsuranceCalculatorController@insuranceTermCoverOutput')->name('frontend.insuranceTermCoverOutput')->middleware(['verifyMembership']);
// Route::post('/calculators/mf-vs-insurance/insurance-term-cover-output', 'Frontend\InsuranceCalculatorController@insuranceTermCoverOutput')->name('frontend.insuranceTermCoverOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/mf-vs-insurance/insurance-term-cover-output-pdf', 'Frontend\InsuranceCalculatorController@insuranceTermCoverOutputPdfDownload')->name('frontend.insuranceTermCoverOutputPdfDownload')->middleware(['verifyMembership']);
// Route::get('/calculators/mf-vs-insurance/insurance-term-cover-output-save', 'Frontend\InsuranceCalculatorController@insuranceTermCoverOutputSave')->name('frontend.insuranceTermCoverOutputSave')->middleware(['verifyMembership']);

//Combination
Route::get('/calculators/combination', 'Frontend\CombinationCalculatorController@index')->name('frontend.combinationIndex');

// Route::get('/calculators/combination/future-value-of-lumpsum-sip', 'Frontend\CombinationCalculatorController@futureValueOfLumpsumSip')->name('frontend.futureValueOfLumpsumSip')->middleware(['verifyMembership']);
// Route::get('/calculators/combination/future-value-of-lumpsum-sip-output', 'Frontend\CombinationCalculatorController@futureValueOfLumpsumSipOutput')->name('frontend.futureValueOfLumpsumSipOutput')->middleware(['verifyMembership']);
// Route::post('/calculators/combination/future-value-of-lumpsum-sip-output', 'Frontend\CombinationCalculatorController@futureValueOfLumpsumSipOutput')->name('frontend.futureValueOfLumpsumSipOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/combination/future-value-of-lumpsum-sip-output-pdf', 'Frontend\CombinationCalculatorController@futureValueOfLumpsumSipOutputPdfDownload')->name('frontend.futureValueOfLumpsumSipOutputPdfDownload')->middleware(['verifyMembership']);
// Route::get('/calculators/combination/future-value-of-lumpsum-sip-output-save', 'Frontend\CombinationCalculatorController@futureValueOfLumpsumSipOutputSave')->name('frontend.futureValueOfLumpsumSipOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/combination/sip-or-lumpsum-investment-required-for-target-future-value', 'Frontend\CombinationCalculatorController@sipLumpsumInvestmentTargetFutureValue')->name('frontend.sipLumpsumInvestmentTargetFutureValue')->middleware(['verifyMembership']);
// Route::get('/calculators/combination/sip-or-lumpsum-investment-required-for-target-future-value-output', 'Frontend\CombinationCalculatorController@sipLumpsumInvestmentTargetFutureValueOutput')->name('frontend.sipLumpsumInvestmentTargetFutureValueOutput')->middleware(['verifyMembership']);
// Route::post('/calculators/combination/sip-or-lumpsum-investment-required-for-target-future-value-output', 'Frontend\CombinationCalculatorController@sipLumpsumInvestmentTargetFutureValueOutput')->name('frontend.sipLumpsumInvestmentTargetFutureValueOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/combination/sip-or-lumpsum-investment-required-for-target-future-value-output-pdf', 'Frontend\CombinationCalculatorController@sipLumpsumInvestmentTargetFutureValueOutputPdfDownload')->name('frontend.sipLumpsumInvestmentTargetFutureValueOutputPdfDownload')->middleware(['verifyMembership']);
// Route::get('/calculators/combination/sip-or-lumpsum-investment-required-for-target-future-value-output-save', 'Frontend\CombinationCalculatorController@sipLumpsumInvestmentTargetFutureValueOutputSave')->name('frontend.sipLumpsumInvestmentTargetFutureValueOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/combination/future-value-of-sip-stp', 'Frontend\CombinationCalculatorController@futureValueOfSipStp')->name('frontend.futureValueOfSipStp')->middleware(['verifyMembership']);
// Route::get('/calculators/combination/future-value-of-sip-stp-output', 'Frontend\CombinationCalculatorController@futureValueOfSipStpOutput')->name('frontend.futureValueOfSipStpOutput')->middleware(['verifyMembership']);
// Route::post('/calculators/combination/future-value-of-sip-stp-output', 'Frontend\CombinationCalculatorController@futureValueOfSipStpOutput')->name('frontend.futureValueOfSipStpOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/combination/future-value-of-sip-stp-output-pdf', 'Frontend\CombinationCalculatorController@futureValueOfSipStpOutputPdfDownload')->name('frontend.futureValueOfSipStpOutputPdfDownload')->middleware(['verifyMembership']);
// Route::get('/calculators/combination/future-value-of-sip-stp-output-save', 'Frontend\CombinationCalculatorController@futureValueOfSipStpOutputSave')->name('frontend.futureValueOfSipStpOutputSave')->middleware(['verifyMembership']);

// Route::get('/calculators/combination/sip-or-stp-required-for-target-future-value', 'Frontend\CombinationCalculatorController@sipStpRequiredForTargetFutureValue')->name('frontend.sipStpRequiredForTargetFutureValue')->middleware(['verifyMembership']);
// Route::get('/calculators/combination/sip-or-stp-required-for-target-future-value-output', 'Frontend\CombinationCalculatorController@sipStpRequiredForTargetFutureValueOutput')->name('frontend.sipStpRequiredForTargetFutureValueOutput')->middleware(['verifyMembership']);
// Route::post('/calculators/combination/sip-or-stp-required-for-target-future-value-output', 'Frontend\CombinationCalculatorController@sipStpRequiredForTargetFutureValueOutput')->name('frontend.sipStpRequiredForTargetFutureValueOutput')->middleware(['verifyMembership']);
// Route::get('/calculators/combination/sip-or-stp-required-for-target-future-value-output-pdf', 'Frontend\CombinationCalculatorController@sipStpRequiredForTargetFutureValueOutputPdfDownload')->name('frontend.sipStpRequiredForTargetFutureValueOutputPdfDownload')->middleware(['verifyMembership']);
// Route::get('/calculators/combination/sip-or-stp-required-for-target-future-value-output-save', 'Frontend\CombinationCalculatorController@sipStpRequiredForTargetFutureValueOutputSave')->name('frontend.sipStpRequiredForTargetFutureValueOutputSave')->middleware(['verifyMembership']);

//Fund Performance
/*Route::get('/calculators/fund-performance', 'Frontend\FundPerformanceController@index')->name('frontend.fundPerformanceIndex')->middleware(['verifyMembership']);
Route::get('/calculators/fund-performance-output', 'Frontend\FundPerformanceController@fundPerformanceOutput')->name('frontend.fundPerformanceOutput')->middleware(['verifyMembership']);
Route::post('/calculators/fund-performance-output', 'Frontend\FundPerformanceController@fundPerformanceOutput')->name('frontend.fundPerformanceOutput')->middleware(['verifyMembership']);
Route::get('/calculators/fund-performance-output-pdf', 'Frontend\FundPerformanceController@fundPerformanceOutputDownloadPdf')->name('frontend.fundPerformanceOutputDownloadPdf')->middleware(['verifyMembership']);
Route::get('/calculators/combination/fund-performance-output-save', 'Frontend\FundPerformanceController@fundPerformanceOutputSave')->name('frontend.fundPerformanceOutputSave')->middleware(['verifyMembership']);*/

Route::get('/calculators/fund-performance', 'Frontend\FundPerformanceController@index')->name('frontend.fundPerformanceIndex')->middleware(['verifyMembership']);
Route::get('/calculators/fund-performance/mylist', 'Frontend\FundPerformanceController@mylist')->name('frontend.fundPerformanceMylist')->middleware(['verifyMembership']);
Route::get('/calculators/fund-performance/mylist-output', 'Frontend\FundPerformanceController@mylistOutput')->name('frontend.fundPerformanceMylistOutput')->middleware(['verifyMembership']);
Route::post('/calculators/fund-performance/mylist-output', 'Frontend\FundPerformanceController@mylistOutput')->name('frontend.fundPerformanceMylistOutput')->middleware(['verifyMembership']);
Route::get('/calculators/fund-performance/mylist-output-save', 'Frontend\FundPerformanceController@mylistOutputSave')->name('frontend.mylistOutputSave')->middleware(['verifyMembership']);
Route::get('/calculators/fund-performance/mylist-download', 'Frontend\FundPerformanceController@mylistDownloadPdf')->name('frontend.fundPerformanceMylistDownloadPdf')->middleware(['verifyMembership']);
Route::get('/calculators/fund-performance/mylist-csv', 'Frontend\FundPerformanceController@mylistDownloadCsv')->name('frontend.fundPerformanceMylistDownloadCsv')->middleware(['verifyMembership']);
Route::post('/calculators/fund-performance/mylist-create', 'Frontend\FundPerformanceController@crateMyList')->name('frontend.fundPerformancecrateMyList')->middleware(['verifyMembership']);
Route::get('/calculators/fund-performance/mylist-save-show/{id}', 'Frontend\FundPerformanceController@mylistSaveShow')->name('frontend.mylistSaveShow')->middleware(['verifyMembership']);

//qqqqqqqqq

Route::get('/calculators/fund-performance/mylist-edit/{id}', 'Frontend\FundPerformanceController@mylistEditShow')->name('frontend.mylistEditShow')->middleware(['verifyMembership']);

Route::post('/calculators/fund-performance/mylist-update/{id}', 'Frontend\FundPerformanceController@mylistUpdate')->name('frontend.mylistUpdate')->middleware(['verifyMembership']);

//qqqqqqqqq

Route::get('/calculators/fund-performance/mylist-save-download/{id}', 'Frontend\FundPerformanceController@mylistSaveDownload')->name('frontend.mylistSaveDownload')->middleware(['verifyMembership']);
Route::get('/calculators/fund-performance/mylist-save-delete/{id}', 'Frontend\FundPerformanceController@mylistSaveDelete')->name('frontend.mylistSaveDelete')->middleware(['verifyMembership']);

Route::get('/calculators/view-mycustomlist-saved-files', 'Frontend\FundPerformanceController@view_mycustomlist_saved_files')->name('frontend.view_mycustomlist_saved_files')->middleware(['verifyMembership']);

//Fund Performance Categorywise
Route::get('/calculators/fund-performance/categorywise', 'Frontend\FundPerformanceCategorywiseController@categorywise')->name('frontend.fundPerformanceCategorywise')->middleware(['verifyMembership']);
Route::get('/calculators/fund-performance/categorywiseschemedetails', 'Frontend\FundPerformanceCategorywiseController@categorywiseschemedetails')->name('frontend.categorywiseschemedetails')->middleware(['verifyMembership']);
Route::post('/calculators/fund-performance/categorywiselist-output', 'Frontend\FundPerformanceCategorywiseController@categorywiselistOutput')->name('frontend.fundPerformanceCategorywiselistOutput')->middleware(['verifyMembership']);
Route::get('/calculators/fund-performance/categorywiselist-output', 'Frontend\FundPerformanceCategorywiseController@categorywiselistOutput')->name('frontend.fundPerformanceCategorywiselistOutput')->middleware(['verifyMembership']);
Route::get('/calculators/fund-performance/categorywiselist-download', 'Frontend\FundPerformanceCategorywiseController@categorywiselistDownloadPdf')->name('frontend.categorywiselistDownloadPdf')->middleware(['verifyMembership']);
Route::get('/calculators/fund-performance/categorywiselist-csv', 'Frontend\FundPerformanceCategorywiseController@categorywiselistDownloadCsv')->name('frontend.categorywiselistDownloadCsv')->middleware(['verifyMembership']);
Route::post('/calculators/fund-performance/categorywiselist-output-save', 'Frontend\FundPerformanceCategorywiseController@categorywiselistOutputSave')->name('frontend.categorywiselistOutputSave')->middleware(['verifyMembership']);
Route::get('/calculators/fund-performance/categorywiselist-save-delete/{id}', 'Frontend\FundPerformanceCategorywiseController@categorywiselistSaveDelete')->name('frontend.categorywiselistSaveDelete')->middleware(['verifyMembership']);
Route::get('/calculators/fund-performance/categorywiselist-save-show/{id}', 'Frontend\FundPerformanceCategorywiseController@categorywiselistSaveShow')->name('frontend.categorywiselistSaveShow')->middleware(['verifyMembership']);
//qqqqqqq
Route::get('/calculators/fund-performance/categorywiselist-save-edit/{id}', 'Frontend\FundPerformanceCategorywiseController@categorywiselistSaveEdit')->name('frontend.categorywiselistSaveEdit')->middleware(['verifyMembership']);

Route::post('/calculators/fund-performance/categorywiselist-output-update/{id}', 'Frontend\FundPerformanceCategorywiseController@categorywiselistOutputUpdate')->name('frontend.categorywiselistOutputUpdate')->middleware(['verifyMembership']);
//qqqqqqq
Route::get('/calculators/fund-performance/categorywiselist-save-download/{id}', 'Frontend\FundPerformanceCategorywiseController@categorywiselistSaveDownloadPdf')->name('frontend.categorywiselistSaveDownloadPdf')->middleware(['verifyMembership']);
Route::get('/calculators/fund-performance/categorywiseschemedetailsrefreshreport', 'Frontend\FundPerformanceCategorywiseController@categorywiseschemedetailsrefreshreport')->name('frontend.categorywiseschemedetailsrefreshreport')->middleware(['verifyMembership']);

Route::get('/calculators/view-categorywise-saved-files', 'Frontend\FundPerformanceCategorywiseController@view_categorywise_saved_files')->name('frontend.view_categorywise_saved_files')->middleware(['verifyMembership']);

//Suggested
Route::get('/suggested/get-asset-type', 'Frontend\SuggestedController@GetAssetType')->name('frontend.GetAssetType');
Route::get('/suggested/get-amc', 'Frontend\SuggestedController@GetAmcList')->name('frontend.GetAmcList');
Route::get('/suggested/get-category', 'Frontend\SuggestedController@GetCategory')->name('frontend.GetCategory');
Route::get('/suggested/get-scheme-dirreg', 'Frontend\SuggestedController@GetSchemeDirreg')->name('frontend.GetSchemeDirreg');
Route::get('/suggested/get-scheme-return-with-nav', 'Frontend\SuggestedController@GetSchemeReturnswithNAV')->name('frontend.GetSchemeReturnswithNAV');
Route::get('/suggested/reset-suggested-scheme', 'Frontend\SuggestedController@resetSuggestedList')->name('frontend.resetSuggestedList');



//Brokerage

Route::get('/brokerage-calculator', 'Frontend\BrokerageCalculatorController@index')->name('frontend.BrokerageIndex');
Route::get('/brokerage-calculator/one-time-sip', 'Frontend\BrokerageCalculatorController@oneTimeSipBrokerage')->name('frontend.oneTimeSipBrokerage')->middleware(['verifyMembership']);
Route::post('/brokerage-calculator/one-time-sip-output', 'Frontend\BrokerageCalculatorController@oneTimeSipBrokerageOutput')->name('frontend.oneTimeSipBrokerageOutput')->middleware(['verifyMembership']);
Route::get('/brokerage-calculator/one-time-sip-output', 'Frontend\BrokerageCalculatorController@oneTimeSipBrokerageOutput')->name('frontend.oneTimeSipBrokerageOutput')->middleware(['verifyMembership']);

Route::get('/brokerage-calculator/monthly-new-sip', 'Frontend\BrokerageCalculatorController@monthlyNewSipBrokerage')->name('frontend.monthlyNewSipBrokerage')->middleware(['verifyMembership']);
Route::get('/brokerage-calculator/monthly-new-sip-output', 'Frontend\BrokerageCalculatorController@monthlyNewSipBrokerageOutput')->name('frontend.monthlyNewSipBrokerageOutput')->middleware(['verifyMembership']);
Route::post('/brokerage-calculator/monthly-new-sip-output', 'Frontend\BrokerageCalculatorController@monthlyNewSipBrokerageOutput')->name('frontend.monthlyNewSipBrokerageOutput')->middleware(['verifyMembership']);

Route::get('/brokerage-calculator/annual-lumpsum', 'Frontend\BrokerageCalculatorController@annualLumpsumSipBrokerage')->name('frontend.annualLumpsumSipBrokerage')->middleware(['verifyMembership']);
Route::get('/brokerage-calculator/annual-lumpsum-output', 'Frontend\BrokerageCalculatorController@annualLumpsumSipBrokerageOutput')->name('frontend.annualLumpsumSipBrokerageOutput')->middleware(['verifyMembership']);
Route::post('/brokerage-calculator/annual-lumpsum-output', 'Frontend\BrokerageCalculatorController@annualLumpsumSipBrokerageOutput')->name('frontend.annualLumpsumSipBrokerageOutput')->middleware(['verifyMembership']);

Route::get('/brokerage-calculator/monthly-lumpsum-sip', 'Frontend\BrokerageCalculatorController@monthlyLumpsumSipBrokerage')->name('frontend.monthlyLumpsumSipBrokerage')->middleware(['verifyMembership']);
Route::get('/brokerage-calculator/monthly-lumpsum-sip-output', 'Frontend\BrokerageCalculatorController@monthlyLumpsumSipBrokerageOutput')->name('frontend.monthlyLumpsumSipBrokerageOutput')->middleware(['verifyMembership']);
Route::post('/brokerage-calculator/monthly-lumpsum-sip-output', 'Frontend\BrokerageCalculatorController@monthlyLumpsumSipBrokerageOutput')->name('frontend.monthlyLumpsumSipBrokerageOutput')->middleware(['verifyMembership']);

Route::get('/brokerage-calculator/current-aum-fresh-new-sales', 'Frontend\BrokerageCalculatorController@currentAumFreshNewSalesSipBrokerage')->name('frontend.currentAumFreshNewSalesSipBrokerage')->middleware(['verifyMembership']);
Route::get('/brokerage-calculator/current-aum-fresh-new-sales-output', 'Frontend\BrokerageCalculatorController@currentAumFreshNewSalesSipBrokerageOutput')->name('frontend.currentAumFreshNewSalesSipBrokerageOutput')->middleware(['verifyMembership']);
Route::post('/brokerage-calculator/current-aum-fresh-new-sales-output', 'Frontend\BrokerageCalculatorController@currentAumFreshNewSalesSipBrokerageOutput')->name('frontend.currentAumFreshNewSalesSipBrokerageOutput')->middleware(['verifyMembership']);

Route::get('/brokerage-calculator/insurance-premium-vs-mutual-fund-lumpsum', 'Frontend\BrokerageCalculatorController@insurancePremiumVsMutualfundLumpsumBrokerage')->name('frontend.insurancePremiumVsMutualfundLumpsumBrokerage')->middleware(['verifyMembership']);
Route::get('/brokerage-calculator/insurance-premium-vs-mutual-fund-lumpsum-output', 'Frontend\BrokerageCalculatorController@insurancePremiumVsMutualfundLumpsumBrokerageOutput')->name('frontend.insurancePremiumVsMutualfundLumpsumBrokerageOutput')->middleware(['verifyMembership']);
Route::post('/brokerage-calculator/insurance-premium-vs-mutual-fund-lumpsum-output', 'Frontend\BrokerageCalculatorController@insurancePremiumVsMutualfundLumpsumBrokerageOutput')->name('frontend.insurancePremiumVsMutualfundLumpsumBrokerageOutput')->middleware(['verifyMembership']);

Route::get('/brokerage-calculator/insurance-premium-vs-mutual-fund-sip', 'Frontend\BrokerageCalculatorController@insurancePremiumVsMutualfundSipBrokerage')->name('frontend.insurancePremiumVsMutualfundSipBrokerage')->middleware(['verifyMembership']);
Route::get('/brokerage-calculator/insurance-premium-vs-mutual-fund-sip-output', 'Frontend\BrokerageCalculatorController@insurancePremiumVsMutualfundSipBrokerageOutput')->name('frontend.insurancePremiumVsMutualfundSipBrokerageOutput')->middleware(['verifyMembership']);
Route::post('/brokerage-calculator/insurance-premium-vs-mutual-fund-sip-output', 'Frontend\BrokerageCalculatorController@insurancePremiumVsMutualfundSipBrokerageOutput')->name('frontend.insurancePremiumVsMutualfundSipBrokerageOutput')->middleware(['verifyMembership']);

Route::get('/brokerage-calculator/single-insurance-policy-vs-one-time-lumpsum-in-mutual-fund', 'Frontend\BrokerageCalculatorController@singleInsurancePolicyVsOnetimeLumpsumInMutualfundBrokerage')->name('frontend.singleInsurancePolicyVsOnetimeLumpsumInMutualfundBrokerage')->middleware(['verifyMembership']);
Route::get('/brokerage-calculator/single-insurance-policy-vs-one-time-lumpsum-in-mutual-fund-output', 'Frontend\BrokerageCalculatorController@singleInsurancePolicyVsOnetimeLumpsumInMutualfundBrokerageOutput')->name('frontend.singleInsurancePolicyVsOnetimeLumpsumInMutualfundBrokerageOutput')->middleware(['verifyMembership']);
Route::post('/brokerage-calculator/single-insurance-policy-vs-one-time-lumpsum-in-mutual-fund-output', 'Frontend\BrokerageCalculatorController@singleInsurancePolicyVsOnetimeLumpsumInMutualfundBrokerageOutput')->name('frontend.singleInsurancePolicyVsOnetimeLumpsumInMutualfundBrokerageOutput')->middleware(['verifyMembership']);

Route::get('/exam/take-exam', 'Frontend\MockExamController@index')->name('frontend.mockExamIndex');
Route::get('/exam/instruction/{id}', 'Frontend\MockExamController@instruction')->name('frontend.instruction')->middleware(['verifyMembership']);
Route::get('/exam/start-exam/{id}', 'Frontend\MockExamController@startExam')->name('frontend.startExam')->middleware(['verifyMembership']);
Route::get('/exam/save-exam-answer', 'Frontend\MockExamController@saveAnswerTmp')->name('frontend.saveAnswerTmp')->middleware(['verifyMembership']);
Route::get('/exam/end-exam', 'Frontend\MockExamController@endExam')->name('frontend.endExam')->middleware(['verifyMembership']);
Route::get('/exam/result-exam/{id}', 'Frontend\MockExamController@resultExam')->name('frontend.resultExam')->middleware(['verifyMembership']);
Route::get('/exam/review-exam/{id}', 'Frontend\MockExamController@reviewExam')->name('frontend.reviewExam')->middleware(['verifyMembership']);

// Only Share

Route::get('/exam/shareable/{id}', 'Frontend\MockExamController@shareable')->name('frontend.exam.shareable');

// Write a testimonials
Route::post('write-a-testimonial/send', 'Frontend\PageController@write_a_testimonialsendMail')->name('frontend.write_a_testimonialsendMail');
Route::post('ask-brijesh/send', 'Frontend\PageController@askBrijeshSendMail')->name('frontend.askBrijeshSendMail');
Route::post('contact-us/send', 'Frontend\PageController@contactSendMail')->name('frontend.contactSendMail');

//Cron
Route::get('/cron/scheme-update', 'Admin\Cron\FundPerformanceCron@schemecodeData');
Route::get('/cron/test-cron-data', 'Admin\Cron\FundPerformanceCron@testCronData');
//update users table 
Route::get('users-update', 'UserController@username_update');
//update displayinfos table 
Route::get('displayinfo-update', 'UserController@displayinfoname_update');

//membership-update
Route::get('/membership-update', 'Frontend\AccountController@membership_update_db')->name('account.subscription.indexmembership-update-db');

// Auto email process for renewal

Route::get('/cron/auto-email-process-renewal-before', 'Admin\Cron\MembershiprenewalmailController@auto_email_process_renewal_before');

Route::get('/cron/auto-email-process-renewal-after', 'Admin\Cron\MembershiprenewalmailController@auto_email_process_renewal_after');

// Package branding

Route::get('/package-download/{id}', 'PackagebrandingController@packagebranding_download')->name('frontend.package-branding.download');
Route::get('/package-branding', 'PackagebrandingController@packagebranding')->name('frontend.package-branding');


    // MF Research 

    Route::get('/screener-saved-files', 'Frontend\MFResearch\MFResearchController@scanner_saved_files')->name('frontend.scanner_saved_files')->middleware(['verifyMembership']);
    Route::get('/screener-delete-saved-file', 'Frontend\MFResearch\MFResearchController@mf_delete_saved_file')->name('frontend.mf_delete_saved_file')->middleware(['verifyMembership']);
    
    
    Route::get('/mf-screener', 'Frontend\MFResearch\MFScannerController@index')->name('frontend.MFScanner');
    Route::post('/mf-screener-save-filter', 'Frontend\MFResearch\MFScannerController@saveFilter')->name('frontend.MFScannerSaveFilter');
    Route::get('/mf-screener-delete-filter', 'Frontend\MFResearch\MFScannerController@deleteFilter')->name('frontend.MFScannerDeleteFilter');
    
    Route::post('/mf-screener-list', 'Frontend\MFResearch\MFScannerController@list')->name('frontend.MFScannerlist');
    
    Route::get('/mf-screener-list', 'Frontend\MFResearch\MFScannerController@list_test')->name('frontend.MFScannerlistget');
    Route::get('/screener-about', 'Frontend\MFResearch\MFScannerController@scanner_about')->name('frontend.scanner_about');
    Route::get('/mf-screener-submit', 'Frontend\MFResearch\MFScannerController@submit')->name('frontend.mf_scanner_submit');
    Route::get('/mf-screener-download', 'Frontend\MFResearch\MFScannerController@download')->name('frontend.mf_scanner_download')->middleware(['verifyMembership']);
    Route::get('/mf-screener/{id}', 'Frontend\MFResearch\MFScannerController@update_screener')->name('frontend.UpdateMFScanner');
    
    Route::get('/mf-screener-compare', 'Frontend\MFResearch\MFScannerCompareController@compare')->name('frontend.mf_scanner_compare');
    Route::get('/mf-screener-compare-remove', 'Frontend\MFResearch\MFScannerCompareController@removeCompare')->name('frontend.mf_scanner_compare_remove');
    Route::get('/mf-screener-compare-download', 'Frontend\MFResearch\MFScannerCompareController@downloadCompare')->name('frontend.mf_scanner_compare_download')->middleware(['verifyMembership']);
    Route::post('/mf-screener-compare-save', 'Frontend\MFResearch\MFScannerCompareController@saveCompare')->name('frontend.mf_scanner_compare_save')->middleware(['verifyMembership']);
    Route::get('/mf-update-compare/{id}', 'Frontend\MFResearch\MFScannerCompareController@update_compare')->name('frontend.mf_update_compare');
    
    Route::get('/mf-screener-scheme-list', 'Frontend\MFResearch\MFScannerController@mf_scanner_scheme_list')->name('frontend.mf_scanner_scheme_list');
    Route::get('/mf-screener-save', 'Frontend\MFResearch\MFScannerController@save')->name('frontend.mf_scanner_save')->middleware(['verifyMembership']);
    Route::get('/screener-view-saved-file-details', 'Frontend\MFResearch\MFResearchController@mf_view_saved_file_details')->name('frontend.mf_view_saved_file_details')->middleware(['verifyMembership']);
    Route::get('/mf-screener-download-saved-file', 'Frontend\MFResearch\MFResearchController@mf_download_saved_file')->name('frontend.mf_download_saved_file')->middleware(['verifyMembership']);
    
    
    Route::get('/mf-stocks-held', 'Frontend\MFResearch\MFStockHeldController@index')->name('frontend.MFStocksHeld');
    Route::get('/mf-stocks-held-scheme-list', 'Frontend\MFResearch\MFStockHeldController@list')->name('frontend.mf_stocks_held_scheme_list');
    Route::get('/mf-stocks-held-action', 'Frontend\MFResearch\MFStockHeldController@stocks_held_action')->name('frontend.stocks_held_action');
    Route::get('/mf-stocks-attracting', 'Frontend\MFResearch\MFStockHeldController@stocks_attracting_fund_managers')->name('frontend.stocks_attracting_fund_managers');
    Route::get('/mf-stocks-seeing', 'Frontend\MFResearch\MFStockHeldController@stocks_seeing_selling_pressure')->name('frontend.stocks_seeing_selling_pressure');
    Route::get('/mf-stocks-bought', 'Frontend\MFResearch\MFStockHeldController@stocks_bought')->name('frontend.mf_stocks_bought');
    Route::get('/mf-stocks-completely-exited', 'Frontend\MFResearch\MFStockHeldController@stocks_completely_exited')->name('frontend.mf_stocks_completely_exited');
    
    Route::get('/mf-rolling-return', 'Frontend\MFResearch\MFRollingReturnController@index')->name('frontend.mf_rolling_return');
    Route::get('/mf-rolling-return-action', 'Frontend\MFResearch\MFRollingReturnController@action')->name('frontend.rolling_return_action');
    Route::post('/mf-rolling-return-list', 'Frontend\MFResearch\MFRollingReturnController@list')->name('frontend.rolling_return_list');
    
    
    Route::get('/mf-category-performance', 'Frontend\MFResearch\MFCategoryPerformanceController@index')->name('frontend.mf_category_performance');
    Route::post('/mf-category-performance-list', 'Frontend\MFResearch\MFCategoryPerformanceController@list')->name('frontend.mf_category_performance_list');
    Route::get('/mf-category-performance-save', 'Frontend\MFResearch\MFCategoryPerformanceController@save')->name('frontend.mf_category_performance_save')->middleware(['verifyMembership']);
    
    
    Route::get('/mf-category-wise-performance', 'Frontend\MFResearch\MFCategoryWisePerformanceController@index')->name('frontend.mf_category_wise_performance');
    Route::post('/mf-category-wise-performance-list', 'Frontend\MFResearch\MFCategoryWisePerformanceController@list')->name('frontend.mf_category_wise_performance_list');
    Route::get('/mf-category-wise-performance-save', 'Frontend\MFResearch\MFCategoryWisePerformanceController@save')->name('frontend.mf_category_wise_performance_save')->middleware(['verifyMembership']);
    Route::get('/mf-category-wise-performance-edit', 'Frontend\MFResearch\MFCategoryWisePerformanceController@edit')->name('frontend.mf_category_wise_performance_edit')->middleware(['verifyMembership']);
    Route::get('/mf-category-wise-performance-view', 'Frontend\MFResearch\MFCategoryWisePerformanceController@view')->name('frontend.mf_category_wise_performance_view')->middleware(['verifyMembership']);
    Route::get('/mf-category-wise-performance-download', 'Frontend\MFResearch\MFCategoryWisePerformanceController@download')->name('frontend.mf_category_wise_performance_download')->middleware(['verifyMembership']);
    Route::get('/mf-category-wise-performance-scheme', 'Frontend\MFResearch\MFCategoryWisePerformanceController@scheme')->name('frontend.mf_category_wise_performance_scheme');
    
    
    Route::get('/mf-best-worst', 'Frontend\MFResearch\MFBestWorstController@index')->name('frontend.mf_best_worst');
    Route::post('/mf-best-worst-list', 'Frontend\MFResearch\MFBestWorstController@list')->name('frontend.mf_best_worst_list');
    Route::get('/mf-best-worst-save', 'Frontend\MFResearch\MFBestWorstController@save')->name('frontend.mf_best_worst_save')->middleware(['verifyMembership']);
    Route::get('/mf-best-worst-edit', 'Frontend\MFResearch\MFBestWorstController@edit')->name('frontend.mf_best_worst_edit')->middleware(['verifyMembership']);
    Route::get('/mf-best-worst-view', 'Frontend\MFResearch\MFBestWorstController@view')->name('frontend.mf_best_worst_view')->middleware(['verifyMembership']);
    Route::get('/mf-best-worst-download', 'Frontend\MFResearch\MFBestWorstController@download')->name('frontend.mf_best_worst_download')->middleware(['verifyMembership']);
    
    
    Route::get('/mf-debt-held', 'Frontend\MFResearch\MFDebtHeldController@index')->name('frontend.MFDebtHeld');
    Route::get('/mf-debt-held-action', 'Frontend\MFResearch\MFDebtHeldController@action')->name('frontend.debt_held_action');
    Route::get('/mf-debt-held-pdf', 'Frontend\MFResearch\MFDebtHeldController@pdf')->name('frontend.debt_held_pdf');
    
    
    Route::get('/mf-page-one', 'Frontend\MFResearch\MFResearchController@mf_page_one')->name('frontend.mf_page_one');
    Route::get('/mf-page-two', 'Frontend\MFResearch\MFResearchController@mf_page_two')->name('frontend.mf_page_two');
    Route::get('/mf-page-three', 'Frontend\MFResearch\MFResearchController@mf_page_three')->name('frontend.mf_page_three');
    Route::get('/mf-page-four', 'Frontend\MFResearch\MFResearchController@mf_page_four')->name('frontend.mf_page_four');
    
    Route::get('/mf-portfolio-analysis', 'Frontend\MFResearch\MFPortfolioAnalysisController@portfolio_analysis')->name('frontend.portfolio_analysis');
    Route::get('/mf-portfolio-analysis-action', 'Frontend\MFResearch\MFPortfolioAnalysisController@portfolio_analysis_action')->name('frontend.portfolio_analysis_action')->middleware(['verifyMembership']);
    Route::get('/mf-portfolio-analysis-action/{schemecode}', 'Frontend\MFResearch\MFPortfolioAnalysisController@csv_portfolio_analysis')->name('frontend.csv_portfolio_analysis')->middleware(['verifyMembership']);
    
    Route::get('/mf-investment-portfolio-analysis', 'Frontend\MFResearch\MFInvestmentPortfolioAnalysisController@investment_analysis')->name('frontend.investment_analysis');
    Route::get('/mf-investment-portfolio-analysis-action', 'Frontend\MFResearch\MFInvestmentPortfolioAnalysisController@investment_analysis_action')->name('frontend.investment_analysis_action');
    Route::post('/mf-investment-portfolio-analysis-ajax-image', 'Frontend\MFResearch\MFInvestmentPortfolioAnalysisController@investment_analysis_image')->name('frontend.investment_analysis_image');
    Route::get('/mf-investment-portfolio-analysis-pfd', 'Frontend\MFResearch\MFInvestmentPortfolioAnalysisController@investment_analysis_pdf')->name('frontend.investment_analysis_pdf');
    
    Route::get('/mf-scheme-snapshot-old', 'Frontend\MFResearch\MFSchemeSnapshotController@index')->name('frontend.factsheet');
    Route::get('/mf-scheme-snapshot', 'Frontend\MFResearch\MFSchemeSnapshotController@new_index')->name('frontend.factsheet_new');
    Route::get('/mf-scheme-snapshot-action', 'Frontend\MFResearch\MFSchemeSnapshotController@action')->name('frontend.factsheet_action')->middleware(['verifyMembership']);
    
    
    
    

Route::get('/nps', 'Frontend\NpsController@index')->name('frontend.nps');

Route::get('/suitability-profiler', 'Frontend\SuitabilityProfilerController@index')->name('frontend.suitability_profiler')->middleware(['verifyMembership']);
Route::get('/suitability-profiler-download-csv', 'Frontend\SuitabilityProfilerController@suitability_profiler_download_csv')->name('frontend.suitability_profiler_download_csv')->middleware(['verifyMembership']);
Route::get('/suitability-profiler-client-list', 'Frontend\SuitabilityProfilerController@suitability_profiler_client_list')->name('frontend.suitability_profiler_client_list')->middleware(['verifyMembership']);
Route::get('/suitability-profiler-client-detail/{id}', 'Frontend\SuitabilityProfilerController@suitability_profiler_client_detail')->name('frontend.suitability_profiler_client_detail');
Route::post('/suitability-profiler-client-add-edit', 'Frontend\SuitabilityProfilerController@suitability_profiler_client_add_edit')->name('frontend.suitability_profiler_client_add_edit')->middleware(['verifyMembership']);
Route::post('/suitability-profiler-client-add-csv', 'Frontend\SuitabilityProfilerController@suitability_profiler_client_add_csv')->name('frontend.suitability_profiler_client_add_csv')->middleware(['verifyMembership']);
Route::post('/suitability-profiler-client-excel-upload', 'Frontend\SuitabilityProfilerController@suitability_profiler_client_excel_upload')->name('frontend.suitability_profiler_client_excel_upload')->middleware(['verifyMembership']);
Route::post('/suitability-profiler-client-sent-email', 'Frontend\SuitabilityProfilerController@suitability_profiler_client_sent_email')->name('frontend.suitability_profiler_client_sent_email')->middleware(['verifyMembership']);

Route::get('/suitability-profiler-client-email', 'Frontend\SuitabilityProfilerController@suitability_profiler_client_email')->name('frontend.suitability_profiler_client_email')->middleware(['verifyMembership']);
Route::post('/suitability-profiler-client-email-save', 'Frontend\SuitabilityProfilerController@suitability_profiler_client_email_save')->name('frontend.suitability_profiler_client_email_save')->middleware(['verifyMembership']);
Route::get('/suitability-profiler-client-email-delete/{id}', 'Frontend\SuitabilityProfilerController@suitability_profiler_client_email_delete')->name('frontend.suitability_profiler_client_email_delete')->middleware(['verifyMembership']);

Route::get('/suitability-profiler-client-delete/{id}', 'Frontend\SuitabilityProfilerController@suitability_profiler_client_delete')->name('frontend.suitability_profiler_client_delete')->middleware(['verifyMembership']);
Route::get('/suitability-profiler-client-import/{id}', 'Frontend\SuitabilityProfilerController@suitability_profiler_client_import')->name('frontend.suitability_profiler_client_import')->middleware(['verifyMembership']);

Route::get('/suitability-profiler-client-details/{id}', 'Frontend\SuitabilityProfilerController@suitability_profiler_client_details')->name('frontend.suitability_profiler_client_details');

Route::get('/suitability-profiler-client-download-pdf/{id}', 'Frontend\SuitabilityProfilerController@suitability_profiler_client_download_pdf')->name('frontend.suitability_profiler_client_download_pdf');

Route::get('/fifa-membership-process', 'Frontend\PageController@fifa_membership_process')->name('frontend.fifa_membership_process');

Route::get('/premade-download-portrait/{order_id}/{user_id}', 'PremadebrandingController@premade_download_portrait')->name('frontend.premade_download_portrait');
Route::get('/premade-download-landscape/{order_id}/{user_id}', 'PremadebrandingController@premade_download_landscape')->name('frontend.premade_download_landscape');

Route::get('/membership', 'Frontend\MembershipController@membership')->name('frontend.membership');

Route::get('/membership/{referral_code}', 'Frontend\MembershipController@membership_referral_code')->name('frontend.membership_referral_code');
Route::get('/membership-cart/{package_id}', 'Frontend\MembershipController@membership_cart')->name('frontend.membership_cart');
Route::get('/membership-cart', 'Frontend\MembershipController@membership_cart')->name('frontend.membership_cart');
Route::get('/membership-carts', 'Frontend\MembershipController@membership_cart_get')->name('frontend.membership_cart_get');
Route::post('/membership-payment', 'Frontend\MembershipController@membership_payment')->name('frontend.membership_payment');
Route::get('/membership-callback', 'Frontend\MembershipController@membership_callback')->name('frontend.membership_callback');
Route::post('/membership-payment-callback/{user_id}/{package_id}/{total_user}', 'Frontend\MembershipController@membership_payment_callback')->name('frontend.membership_payment_callback');

Route::get('/membership-update', 'Frontend\AccountController@membership_update')->name('frontend.membership_update_package');
Route::post('/membership-update-payment', 'Frontend\AccountController@membership_update_payment')->name('frontend.membership_update_payment');
Route::post('/membership-update-payment-callback/{user_id}/{package_id}/{total_user}', 'Frontend\MembershipController@membership_update_payment_callback')->name('frontend.membership_update_payment_callback');

Route::get('/randhir-resend-email/{order_id}', 'RandhirController@resend_email')->name('frontend.randhir_resend_email');

Route::get('other-downloads', 'Frontend\FamousQuotesController@index')->name('frontend.famous_quotes');
Route::get('other-downloads/{id}', 'Frontend\FamousQuotesController@detail')->name('frontend.famous_quotes_detail');
Route::get('other-downloads-download/{id}', 'Frontend\FamousQuotesController@download')->name('frontend.famous_quotes_download')->middleware(['verifyMembership']);

Route::get('/subscriptions/subscription-cart/{id}', 'Frontend\SubscriptionController@cart')->name('frontend.subscriptionCart');
Route::get('/subscriptions/success', 'Frontend\SubscriptionController@success')->name('frontend.subscriptionSuccess');
Route::post('/subscriptions/subscription-payment', 'Frontend\SubscriptionController@payment')->name('frontend.subscriptionPayment');
Route::post('/subscriptions-payment-callback/{user_id}/{package_id}/{membership_id}', 'Frontend\SubscriptionController@paymentCallback')->name('frontend.subscriptionPaymentCallback');

Route::get('/premium-calculator/index', 'Frontend\PremiumCalculatorController@index')->name('frontend.premium_calculator');
// Route::get('/premium-calculator/recover_emis_through_sip', 'Frontend\PremiumCalculatorController@recover_emis_through_sip')->name('frontend.recover_emis_through_sip')->middleware(['verifyMembership']);
// Route::post('/premium-calculator/recover_emis_through_sip_output', 'Frontend\PremiumCalculatorController@recover_emis_through_sip_output')->name('frontend.recover_emis_through_sip_output')->middleware(['verifyMembership']);
// Route::get('/premium-calculator/recover_emis_through_sip_output', 'Frontend\PremiumCalculatorController@recover_emis_through_sip_output')->name('frontend.recover_emis_through_sip_output')->middleware(['verifyMembership']);
// Route::get('/premium-calculator/recover_emis_through_sip_output_pdf', 'Frontend\PremiumCalculatorController@recover_emis_through_sip_output_pdf')->name('frontend.recover_emis_through_sip_output_pdf')->middleware(['verifyMembership']);
// Route::get('/premium-calculator/recover_emis_through_sips_output_save', 'Frontend\PremiumCalculatorController@recover_emis_through_sips_output_save')->name('frontend.recover_emis_through_sips_output_save');

// Route::get('/premium-calculator/portfolio_projection', 'Frontend\PremiumCalculatorController@portfolio_projection')->name('frontend.portfolio_projection')->middleware(['verifyMembership']);
// Route::post('/premium-calculator/portfolio_projection_output', 'Frontend\PremiumCalculatorController@portfolio_projection_output')->name('frontend.portfolio_projection_output')->middleware(['verifyMembership']);
// Route::get('/premium-calculator/portfolio_projection_output_save', 'Frontend\PremiumCalculatorController@portfolio_projection_output_save')->name('frontend.portfolio_projection_output_save')->middleware(['verifyMembership']);
// Route::get('/premium-calculator/portfolio_projection_output_pdf', 'Frontend\PremiumCalculatorController@portfolio_projection_output_pdf')->name('frontend.portfolio_projection_output_pdf')->middleware(['verifyMembership']);

Route::get('/premium-calculator/investment_proposal', 'Frontend\InvestmentProposalController@index')->name('frontend.investment_proposal')->middleware(['verifyMembership']);
Route::get('/premium-calculator/investment_proposal_old', 'Frontend\InvestmentProposalController@index_old')->name('frontend.investment_proposal_old')->middleware(['verifyMembership']);
Route::post('/premium-calculator/investment_proposal_output', 'Frontend\InvestmentProposalController@output')->name('frontend.investment_proposal_output')->middleware(['verifyMembership']);
Route::get('/premium-calculator/investment_proposal_output_save', 'Frontend\InvestmentProposalController@save')->name('frontend.investment_proposal_output_save')->middleware(['verifyMembership']);
Route::get('/premium-calculator/investment_proposal_output_pdf', 'Frontend\InvestmentProposalController@pdf')->name('frontend.investment_proposal_output_pdf')->middleware(['verifyMembership']);

// Route::get('/premium-calculator/hlv_calculation', 'Frontend\PremiumCalculatorController@hlv_calculation')->name('frontend.hlv_calculation')->middleware(['verifyMembership']);
// Route::post('/premium-calculator/hlv_calculation_output', 'Frontend\PremiumCalculatorController@hlv_calculation_output')->name('frontend.hlv_calculation_output')->middleware(['verifyMembership']);
// Route::get('/premium-calculator/hlv_calculation_output', 'Frontend\PremiumCalculatorController@hlv_calculation_output')->name('frontend.hlv_calculation_output')->middleware(['verifyMembership']);
// Route::get('/premium-calculator/hlv_calculation_output_pdf', 'Frontend\PremiumCalculatorController@hlv_calculation_output_pdf')->name('frontend.hlv_calculation_output_pdf')->middleware(['verifyMembership']);
// Route::get('/premium-calculator/hlv_calculation_output_save', 'Frontend\PremiumCalculatorController@hlv_calculation_output_save')->name('frontend.hlv_calculation_output_save')->middleware(['verifyMembership']);

Route::get('/premium-calculator/goal_calculator', 'Frontend\GoalCalculatorController@index')->name('frontend.goal_calculator')->middleware(['verifyMembership']);
Route::post('/premium-calculator/goal_calculator_output', 'Frontend\GoalCalculatorController@goal_calculator_output')->name('frontend.goal_calculator_output')->middleware(['verifyMembership']);
Route::get('/premium-calculator/goal_calculator_output', 'Frontend\GoalCalculatorController@goal_calculator_output')->name('frontend.goal_calculator_output')->middleware(['verifyMembership']);
Route::get('/premium-calculator/goal_calculator_output_save', 'Frontend\GoalCalculatorController@goal_calculator_output_save')->name('frontend.goal_calculator_output_save')->middleware(['verifyMembership']);
Route::get('/premium-calculator/goal_calculator_output_pdf', 'Frontend\GoalCalculatorController@goal_calculator_output_pdf')->name('frontend.goal_calculator_output_pdf')->middleware(['verifyMembership']);

// Route::get('/premium-calculator/debt_fund_trade_off', 'Frontend\PremiumCalculatorController@debt_fund_trade_off')->name('frontend.debt_fund_trade_off')->middleware(['verifyMembership']);
// Route::post('/premium-calculator/debt_fund_trade_off_output', 'Frontend\PremiumCalculatorController@debt_fund_trade_off_output')->name('frontend.debt_fund_trade_off_output')->middleware(['verifyMembership']);
// Route::get('/premium-calculator/debt_fund_trade_off_output_pdf', 'Frontend\PremiumCalculatorController@debt_fund_trade_off_output_pdf')->name('frontend.debt_fund_trade_off_output_pdf')->middleware(['verifyMembership']);
// Route::get('/premium-calculator/debt_fund_trade_off_output_save', 'Frontend\PremiumCalculatorController@debt_fund_trade_off_output_save')->name('frontend.debt_fund_trade_off_output_save')->middleware(['verifyMembership']);

// Route::get('/premium-calculator/capital_gains_tax_calculator', 'Frontend\PremiumCalculatorController@capital_gains_tax_calculator')->name('frontend.capital_gains_tax_calculator')->middleware(['verifyMembership']);
// Route::post('/premium-calculator/capital_gains_tax_calculator_output', 'Frontend\PremiumCalculatorController@capital_gains_tax_calculator_output')->name('frontend.capital_gains_tax_calculator_output')->middleware(['verifyMembership']);
// Route::get('/premium-calculator/capital_gains_tax_calculator_output', 'Frontend\PremiumCalculatorController@capital_gains_tax_calculator_output')->name('frontend.capital_gains_tax_calculator_output')->middleware(['verifyMembership']);
// Route::get('/premium-calculator/capital_gains_tax_calculator_output_save', 'Frontend\PremiumCalculatorController@capital_gains_tax_calculator_output_save')->name('frontend.capital_gains_tax_calculator_output_save')->middleware(['verifyMembership']);
// Route::get('/premium-calculator/capital_gains_tax_calculator_output_pdf', 'Frontend\PremiumCalculatorController@capital_gains_tax_calculator_output_pdf')->name('frontend.capital_gains_tax_calculator_output_pdf')->middleware(['verifyMembership']);

Route::get('/previous-webinar', 'Frontend\PreviousWebinarController@previous_webinar')->name('frontend.previous-webinar');
Route::get('/previous-webinar-most-viewed', 'Frontend\PreviousWebinarController@free_most_viewed_videos')->name('frontend.free-previous-webinar-most-viewed');
Route::get('/marketting-paid-previous-webinar', 'Frontend\PreviousWebinarController@paid_videos')->name('frontend.paid-previous-webinar')->middleware(['verifyMembership']);
Route::get('/previous-webinar/{slug}', 'Frontend\PreviousWebinarController@free_videos_details')->name('frontend.marketting-free-previous-webinar-details');



Route::get('/calculators/category-wise/{id}', 'Frontend\CalculatorController@categoryWise')->name('frontend.calculatorCategoryWise');


Route::get('/calculators/all', 'Frontend\CalculatorController@allList')->name('frontend.calculatorAllList');
Route::get('/calculators/star', 'Frontend\CalculatorController@star')->name('frontend.calculators_star')->middleware(['verifyMembership']);

Route::get('/calculators/sample-report/{type}/{id}', 'Frontend\CalculatorController@samplereports')->name('frontend.calculatorSampleReport')->middleware(['verifyMembership']);

Route::get('/mso-associate', 'Frontend\HomeController@msoAssociate')->name('frontend.mso-associate');
Route::post('/mso-associate', 'Frontend\HomeController@businessAssociate')->name('frontend.business_associate');

Route::get('/sales-presenters/category/{slug}', 'Frontend\SalesPresenterPdfController@category')->name('frontend.sales-presenters.category');

Route::get('/client-communication', 'Frontend\ClientCommunicationController@index')->name('frontend.client-communication')->middleware(['verifyMembership']);
Route::get('/client-communication/category/{slug}', 'Frontend\ClientCommunicationController@category')->name('frontend.client-communication.category')->middleware(['verifyMembership']);
Route::get('/client-communication/history-data-save', 'Frontend\ClientCommunicationController@history_save_data')->name('frontend.client-communication.history_save_data')->middleware(['verifyMembership']);

//Mark Notification as Read
Route::post('/notification-read', 'Admin\NotificationController@readNotification')->name('frontend.readNotification');

// PDF Cover Image 
Route::post('/account/display-pdfcover-update/{id}','Frontend\AccountController@coverImageUpdate')->name('account.display-pdfcover.update');

Route::group(['middleware' => 'verifyMembership'], function () {
    Route::get('/readymade-portfolio-old','Frontend\ReadymadePortfolioController@index')->name('frontend.readymadePortfolio.index_old');
    Route::get('/readymade-portfolio','Frontend\ReadymadePortfolioController@index_new')->name('frontend.readymadePortfolio.index');

    Route::get('/readymade-portfolio/output-new/{id}','Frontend\ReadymadePortfolioController@output_new')->name('frontend.readymadePortfolio.output_new');

    Route::post('/readymade-portfolio/pdf','Frontend\ReadymadePortfolioController@pdf')->name('frontend.readymadePortfolio.pdf');
    Route::post('/readymade-portfolio/output','Frontend\ReadymadePortfolioController@output')->name('frontend.readymadePortfolio.output');
    
    Route::get('/readymade-portfolio-checkbox','Frontend\ReadymadePortfolioController@checkbox')->name('frontend.readymadePortfolio.checkbox');
    
    
    //Welcome Letter
    
    Route::get('/welcome-letter','Frontend\WelcomeLetterController@index')->name('frontend.welcomeletter.index');
    Route::get('/welcome-letter/{slug}','Frontend\WelcomeLetterController@index')->name('frontend.welcomeletter.slug');
    Route::get('/welcome-letter-add','Frontend\WelcomeLetterController@add')->name('frontend.welcomeletter.add');
    Route::post('/welcome-letter-save','Frontend\WelcomeLetterController@save')->name('frontend.welcomeletter.save');
    Route::get('/welcome-letter-edit/{user}/{id}','Frontend\WelcomeLetterController@edit')->name('frontend.welcomeletter.edit');
    Route::post('/welcome-letter-update','Frontend\WelcomeLetterController@update')->name('frontend.welcomeletter.update');
    Route::get('/welcome-letter-pdf/{id}/{task}','Frontend\WelcomeLetterController@download_pdf')->name('frontend.welcomeletter.pdf');
    Route::get('/welcome-letter-copy/{id}','Frontend\WelcomeLetterController@generate_view')->name('frontend.welcomeletter.generate_view');
    Route::post('/welcome-letter-sendmail','Frontend\WelcomeLetterController@sendmail')->name('frontend.welcomeletter.sendmail');
    Route::get('/welcome-letter-delete/{id}','Frontend\WelcomeLetterController@deleteTemplate')->name('frontend.welcomeletter.delete');

    Route::get('/welcome-letter-order/{slug}','Frontend\WelcomeLetterController@order')->name('frontend.welcomeletter.order');
    Route::post('/welcome-letter-order/{slug}','Frontend\WelcomeLetterController@updateOrder');
    
    Route::post('/mark-as-read', 'Admin\NotificationController@resetNotificationCount')->name('frontend.resetNotificationCount');

    Route::get('/hidePopups', 'Admin\NotificationController@hidePopups')->name('frontend.hidePopups');
  
});

  
    //Demo
    Route::get('/home-demo','Frontend\DemoController@index')->name('frontend.demo.index');
    Route::get('/home-demo-d','Frontend\DemoController@indexd')->name('frontend.demo.indexd');
    Route::post('/demo-save','Frontend\DemoController@save')->name('frontend.demo.save');
    Route::post('/demo-save-d','Frontend\DemoController@saved')->name('frontend.demo.saved');
    Route::get('/demo-sendmail/{id}','Frontend\DemoController@sendmail')->name('frontend.demo.sendmail');
    Route::get('/demo-success','Frontend\DemoController@success')->name('frontend.demo.success');
    
    Route::post('/get_city','Frontend\DemoController@getCity')->name('frontend.city.get');
    

    //National Conference
    Route::get('/national-conference','Frontend\NationalConferenceController@index')->name('frontend.conference.index');
    Route::get('/national-conference-register/{type}','Frontend\NationalConferenceController@register')->name('frontend.conference.register');
    Route::post('/national-conference-save','Frontend\NationalConferenceController@save')->name('frontend.conference.save');
    Route::get('/national-conference-sendmail/{id}','Frontend\NationalConferenceController@sendmail')->name('frontend.conference.sendmail');
    
    
    Route::get('/mf-swp-historical', 'Frontend\MFResearch\MFSWPLiveController@index')->name('frontend.mf_swp_historical');
    Route::get('/mf-swp-historical-action', 'Frontend\MFSWPLiveController@action')->name('frontend.mf_swp_historical_action');
	Route::post('/mf-swp-historical-list', 'Frontend\MFSWPLiveController@list')->name('frontend.mf_swp_historical_list');
	Route::post('/mf-swp-historical-save-xirr', 'Frontend\MFSWPLiveController@save_xirr')->name('frontend.mf_swp_historical_save_xirr');
	
	
    Route::get('/mf-stp-historical', 'Frontend\MFResearch\MFStpLiveController@index')->name('frontend.mf_stp_historical');
    Route::get('/get-scheme-amcwise-list/{amc_code}', 'Frontend\MFStpLiveController@schemeList')->name('frontend.get_scheme_amcwise_list');
    Route::post('/mf-stp-historical-action', 'Frontend\MFStpLiveController@action')->name('frontend.mf_stp_historical_action');
	Route::get('/mf-stp-historical-list', 'Frontend\MFStpLiveController@list')->name('frontend.mf_stp_historical_list');

    Route::get('/upgrade-premium-membership-trial', 'Frontend\AccountController@upgradePremiumMembershipTrial')->name('account.upgradePremiumMembershipTrial');
    
    
    Route::get('/mf-sip-return', 'Frontend\MFSipReturnController@index')->name('frontend.mf_rolling_return');
    Route::post('/mf-sip-return-list', 'Frontend\MFSipReturnController@list')->name('frontend.rolling_return_list');
    
    
	
	//Mso portfolio routes
	Route::get('mso-model-portfolio','Frontend\MsoPortfolioController@msoindex')->name('frontend.msoportfolioinput')->middleware(['verifyMembership']);
	Route::get('mso-model-portfolio/pdf','Frontend\MsoPortfolioController@msopdf')->name('frontend.msoportfoliopdf')->middleware(['verifyMembership']);
	Route::post('mso-model-portfolio/output','Frontend\MsoPortfolioController@msoOutput')->name('frontend.mso-model-portfolio.output')->middleware(['verifyMembership']);
     
    Route::post('/mso-model-portfolio/lumpsum','Frontend\MsoPortfolioController@lumpsum')->name('frontend.mso-model-portfolio.lumpsum');
    Route::post('/mso-model-portfolio/sip','Frontend\MsoPortfolioController@sip')->name('frontend.mso-model-portfolio.sip');
     Route::post('/mso-model-portfolio/stp','Frontend\MsoPortfolioController@stp')->name('frontend.mso-model-portfolio.stp');
     Route::post('/mso-model-portfolio/swp','Frontend\MsoPortfolioController@stp')->name('frontend.mso-model-portfolio.stp');

     Route::post('/mso-model-portfolio/category_list','Frontend\MsoPortfolioController@category_list')->name('frontend.mso-model-portfolio.category_list');

     Route::post('/mso-model-portfolio/getInputs','Frontend\MsoPortfolioController@getValues')->name('frontend.mso-model-portfolio.getInputs');
     Route::post('/mso-model-portfolio/getAllScheme','Frontend\MsoPortfolioController@GetMostSchemes')->name('frontend.mso-model-portfolio.getAllScheme');
     



	Route::get('/trigger/get-data', 'Frontend\TriggerController@getData')->name('frontend.trigger_get_data')->middleware(['verifyMembership']);
	
	Route::get('/trigger/list', 'Frontend\TriggerController@list')->name('frontend.trigger_list')->middleware(['verifyMembership']);
	Route::get('/trigger/add', 'Frontend\TriggerController@add')->name('frontend.trigger_add')->middleware(['verifyMembership']);
	Route::post('/trigger/save', 'Frontend\TriggerController@save')->name('frontend.trigger_save')->middleware(['verifyMembership']);
	Route::get('/trigger/edit', 'Frontend\TriggerController@edit')->name('frontend.trigger_edit')->middleware(['verifyMembership']);
	Route::post('/trigger/update', 'Frontend\TriggerController@update')->name('frontend.trigger_update')->middleware(['verifyMembership']);
	Route::get('/trigger/delete', 'Frontend\TriggerController@delete')->name('frontend.trigger_delete')->middleware(['verifyMembership']);
	Route::get('/trigger/delete-all', 'Frontend\TriggerController@delete_all')->name('frontend.trigger_delete_all')->middleware(['verifyMembership']);
	Route::get('/trigger/setting', 'Frontend\TriggerController@setting')->name('frontend.trigger_setting')->middleware(['verifyMembership']);
	Route::post('/trigger/setting-update', 'Frontend\TriggerController@setting_update')->name('frontend.trigger_setting_update')->middleware(['verifyMembership']);
	Route::get('/trigger/default', 'Frontend\TriggerController@default')->name('frontend.trigger_default')->middleware(['verifyMembership']);
	Route::get('/trigger/completed', 'Frontend\TriggerController@completed')->name('frontend.trigger_completed')->middleware(['verifyMembership']);
	Route::get('/trigger/subscribe', 'Frontend\TriggerController@subscribe')->name('frontend.trigger_subscribe')->middleware(['verifyMembership']);


	Route::get('/calculators/onetime-investment/one-time-investment-required-for-future-goal', 'Frontend\Calculators\LumsumInvestmentRequiredForTargetFutureValueController@index')->name('frontend.lumsumInvestmentRequiredForTargetFutureValue')->middleware(['verifyMembership']);
	Route::get('/calculators/onetime-investment/one-time-investment-required-for-future-goal-back', 'Frontend\Calculators\LumsumInvestmentRequiredForTargetFutureValueController@index')->name('frontend.lumsumInvestmentRequiredForTargetFutureValueBack')->middleware(['verifyMembership']);
	Route::post('/calculators/onetime-investment/one-time-investment-required-for-future-goal-output', 'Frontend\Calculators\LumsumInvestmentRequiredForTargetFutureValueController@output')->name('frontend.lumsumInvestmentRequiredForTargetFutureValueOutput')->middleware(['verifyMembership']);
	Route::get('/calculators/onetime-investment/one-time-investment-required-for-future-goal-output', 'Frontend\Calculators\LumsumInvestmentRequiredForTargetFutureValueController@output')->name('frontend.lumsumInvestmentRequiredForTargetFutureValueOutput')->middleware(['verifyMembership']);
	Route::get('/calculators/onetime-investment/one-time-investment-required-for-future-goal-output-pdf', 'Frontend\Calculators\LumsumInvestmentRequiredForTargetFutureValueController@pdf')->name('frontend.lumsumInvestmentRequiredForTargetFutureValuePdf')->middleware(['verifyMembership']);
	Route::get('/calculators/onetime-investment/one-time-investment-required-for-future-goal-edit', 'Frontend\Calculators\LumsumInvestmentRequiredForTargetFutureValueController@edit')->name('frontend.lumsumInvestmentRequiredForTargetFutureValueEdit')->middleware(['verifyMembership']);
	Route::get('/calculators/onetime-investment/one-time-investment-required-for-future-goal-view', 'Frontend\Calculators\LumsumInvestmentRequiredForTargetFutureValueController@view')->name('frontend.lumsumInvestmentRequiredForTargetFutureValueView')->middleware(['verifyMembership']);
	Route::get('/calculators/onetime-investment/one-time-investment-required-for-future-goal-merge-download', 'Frontend\Calculators\LumsumInvestmentRequiredForTargetFutureValueController@merge_download')->name('frontend.lumsumInvestmentRequiredForTargetFutureValueMergeDownload')->middleware(['verifyMembership']);
	Route::get('/calculators/onetime-investment/one-time-investment-required-for-future-goal-output-save', 'Frontend\Calculators\LumsumInvestmentRequiredForTargetFutureValueController@save')->name('frontend.lumsumInvestmentRequiredForTargetFutureValueSave')->middleware(['verifyMembership']);
	
	Route::get('/calculators/onetime-investment/one-time-investment-ready-reckoner', 'Frontend\Calculators\OneTimeInvestmentGoalPlanningReadyReckonerController@one_time_investment_ready_reckoner')->name('frontend.oneTimeInvestmentReadyReckoner')->middleware(['verifyMembership']);
    Route::post('/calculators/onetime-investment/one-time-investment-ready-reckoner-output', 'Frontend\Calculators\OneTimeInvestmentGoalPlanningReadyReckonerController@one_time_investment_ready_reckoner_output')->name('frontend.oneTimeInvestmentReadyReckonerOutput')->middleware(['verifyMembership']);
    Route::get('/calculators/onetime-investment/one-time-investment-ready-reckoner-output', 'Frontend\Calculators\OneTimeInvestmentGoalPlanningReadyReckonerController@one_time_investment_ready_reckoner_output')->name('frontend.oneTimeInvestmentReadyReckonerOutput')->middleware(['verifyMembership']);
    Route::get('/calculators/onetime-investment/one-time-investment-ready-reckoner-output-pdf', 'Frontend\Calculators\OneTimeInvestmentGoalPlanningReadyReckonerController@one_time_investment_ready_reckoner_output_pdf_download')->name('frontend.oneTimeInvestmentReadyReckonerOutputPdf')->middleware(['verifyMembership']);
    Route::get('/calculators/onetime-investment/one-time-investment-ready-reckoner-output-save', 'Frontend\Calculators\OneTimeInvestmentGoalPlanningReadyReckonerController@one_time_investment_ready_reckoner_output_save')->name('frontend.oneTimeInvestmentReadyReckonerOutputSave')->middleware(['verifyMembership']);
    Route::get('/calculators/onetime-investment/one-time-investment-ready-reckoner-back', 'Frontend\Calculators\OneTimeInvestmentGoalPlanningReadyReckonerController@one_time_investment_ready_reckoner')->name('frontend.oneTimeInvestmentReadyReckonerBack')->middleware(['verifyMembership']);
    Route::get('/calculators/onetime-investment/one-time-investment-ready-reckoner-view', 'Frontend\Calculators\OneTimeInvestmentGoalPlanningReadyReckonerController@view')->name('frontend.oneTimeInvestmentReadyReckonerView')->middleware(['verifyMembership']);
    Route::get('/calculators/onetime-investment/one-time-investment-ready-reckoner-edit', 'Frontend\Calculators\OneTimeInvestmentGoalPlanningReadyReckonerController@one_time_investment_ready_reckoner_edit')->name('frontend.oneTimeInvestmentReadyReckoneredit')->middleware(['verifyMembership']);
    Route::get('/calculators/onetime-investment/one-time-investment-ready-reckoner-merge-download', 'Frontend\Calculators\OneTimeInvestmentGoalPlanningReadyReckonerController@merge_download')->name('frontend.oneTimeInvestmentReadyReckonerMergeDownload')->middleware(['verifyMembership']);

	
	Route::get('/motilal/index', 'Frontend\MotilalController@index')->name('frontend.motilal_index');
	Route::get('/motilal/test1', 'Frontend\MotilalController@test1')->name('frontend.motilal_test1');
	Route::get('/motilal/test2', 'Frontend\MotilalController@test2')->name('frontend.motilal_test2');
	
	Route::get('/client-communication-details/{id}', 'Frontend\ClientCommunicationController@details')->name('frontend.client-communication-details')->middleware(['verifyMembership']);
	
	
	
	Route::get('/library/index', 'Frontend\LibraryController@index')->name('frontend.library_index')->middleware(['verifyMembership']);
	Route::get('/library/back', 'Frontend\LibraryController@back')->name('frontend.library_back')->middleware(['verifyMembership']);
	Route::get('/library/output', 'Frontend\LibraryController@output')->name('frontend.library_output')->middleware(['verifyMembership']);
	Route::get('/library/save', 'Frontend\LibraryController@save')->name('frontend.library_save')->middleware(['verifyMembership']);
	Route::get('/library/pdf', 'Frontend\LibraryController@pdf')->name('frontend.library_pdf')->middleware(['verifyMembership']);
	Route::get('/library/saved-list', 'Frontend\LibraryController@saved_list')->name('frontend.library_saved_list')->middleware(['verifyMembership']);
	Route::get('/library/saved-delete', 'Frontend\LibraryController@saved_delete')->name('frontend.library_saved_delete')->middleware(['verifyMembership']);
	Route::get('/library/edit', 'Frontend\LibraryController@edit')->name('frontend.library_edit')->middleware(['verifyMembership']);
	Route::get('/library/getViewControllerString', 'Frontend\LibraryController@getViewControllerString')->name('frontend.updatePdfViewPaths')->middleware(['verifyMembership']);
	
	
    
//swp_debt_balanc_fund_calculator
Route::get('/calculators/swp-calculator/swp-debt-balanc-fund-calculator', 'Frontend\Calculators\SwpDebtBalancFundController@index')->name('frontend.swp_debt_balanc_fund_calculator')->middleware(['verifyMembership']);
Route::post('/calculators/swp-calculator/swp-debt-balanc-fund-calculator-output', 'Frontend\Calculators\SwpDebtBalancFundController@output')->name('frontend.swp_debt_balanc_fund_calculator_output')->middleware(['verifyMembership']);
Route::post('/calculators/swp-calculator/swp-debt-balanc-fund-calculator-ajax', 'Frontend\Calculators\SwpDebtBalancFundController@swp_debt_balanc_fund_calculator_ajax')->name('frontend.swp_debt_balanc_fund_calculator_ajax')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/swp-debt-balanc-fund-calculator-pdf', 'Frontend\Calculators\SwpDebtBalancFundController@pdf')->name('frontend.swp_debt_balanc_fund_calculator_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/swp-debt-balanc-fund-calculator-save', 'Frontend\Calculators\SwpDebtBalancFundController@save')->name('frontend.swp_debt_balanc_fund_calculator_save')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/swp-debt-balanc-fund-calculator-back', 'Frontend\Calculators\SwpDebtBalancFundController@index')->name('frontend.swp_debt_balanc_fund_calculator_back')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/swp-debt-balanc-fund-calculator-view', 'Frontend\Calculators\SwpDebtBalancFundController@view')->name('frontend.swp_debt_balanc_fund_calculator_view')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/swp-debt-balanc-fund-calculator-edit', 'Frontend\Calculators\SwpDebtBalancFundController@edit')->name('frontend.swp_debt_balanc_fund_calculator_edit')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/swp-debt-balanc-fund-calculator-merge-download', 'Frontend\Calculators\SwpDebtBalancFundController@merge_download')->name('frontend.swp_debt_balanc_fund_calculator_merge_download')->middleware(['verifyMembership']);


//recover_emis_through_sip
Route::get('/premium-calculator/recover_emis_through_sip', 'Frontend\Calculators\RecoverEmiThroughSIPController@index')->name('frontend.recover_emis_through_sip')->middleware(['verifyMembership']);
Route::post('/premium-calculator/recover_emis_through_sip-output', 'Frontend\Calculators\RecoverEmiThroughSIPController@output')->name('frontend.recover_emis_through_sip_output')->middleware(['verifyMembership']);
Route::get('/premium-calculator/recover_emis_through_sip-pdf', 'Frontend\Calculators\RecoverEmiThroughSIPController@pdf')->name('frontend.recover_emis_through_sip_pdf')->middleware(['verifyMembership']);
Route::get('/premium-calculator/recover_emis_through_sip-save', 'Frontend\Calculators\RecoverEmiThroughSIPController@save')->name('frontend.recover_emis_through_sip_save')->middleware(['verifyMembership']);
Route::get('/premium-calculator/recover_emis_through_sip-back', 'Frontend\Calculators\RecoverEmiThroughSIPController@index')->name('frontend.recover_emis_through_sip_back')->middleware(['verifyMembership']);
Route::get('/premium-calculator/recover_emis_through_sip-view', 'Frontend\Calculators\RecoverEmiThroughSIPController@view')->name('frontend.recover_emis_through_sip_view')->middleware(['verifyMembership']);
Route::get('/premium-calculator/recover_emis_through_sip-edit', 'Frontend\Calculators\RecoverEmiThroughSIPController@edit')->name('frontend.recover_emis_through_sip_edit')->middleware(['verifyMembership']);
Route::get('/premium-calculator/recover_emis_through_sip-merge-download', 'Frontend\Calculators\RecoverEmiThroughSIPController@merge_download')->name('frontend.recover_emis_through_sip__merge_download')->middleware(['verifyMembership']);

//need-based-calculator
Route::get('/calculators/need-based-calculator/child-education-need-based-calculator', 'Frontend\Calculators\ChildEduMarriageExpenseController@index')->name('frontend.childEducation')->middleware(['verifyMembership']);
Route::post('/calculators/need-based-calculator/child-education-need-based-calculator-output', 'Frontend\Calculators\ChildEduMarriageExpenseController@output')->name('frontend.childEducation_output')->middleware(['verifyMembership']);
Route::get('/calculators/need-based-calculator/child-education-need-based-calculator-pdf', 'Frontend\Calculators\ChildEduMarriageExpenseController@pdf')->name('frontend.childEducation_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/need-based-calculator/child-education-need-based-calculator-save', 'Frontend\Calculators\ChildEduMarriageExpenseController@save')->name('frontend.childEducation_save')->middleware(['verifyMembership']);
Route::get('/calculators/need-based-calculator/child-education-need-based-calculator-back', 'Frontend\Calculators\ChildEduMarriageExpenseController@index')->name('frontend.childEducation_back')->middleware(['verifyMembership']);
Route::get('/calculators/need-based-calculator/child-education-need-based-calculator-view', 'Frontend\Calculators\ChildEduMarriageExpenseController@view')->name('frontend.childEducation_view')->middleware(['verifyMembership']);
Route::get('/calculators/need-based-calculator/child-education-need-based-calculator-edit', 'Frontend\Calculators\ChildEduMarriageExpenseController@edit')->name('frontend.childEducation_edit')->middleware(['verifyMembership']);
Route::get('/calculators/need-based-calculator/child-education-need-based-calculator-merge-download', 'Frontend\Calculators\ChildEduMarriageExpenseController@merge_download')->name('frontend.childEducation_merge_download')->middleware(['verifyMembership']);

// limited-period-sip-calculator
Route::get('/calculators/sip-calculator/limited-period-sip-calculator', 'Frontend\Calculators\FutureValueLimitedPeriodSIPController@index')->name('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod')->middleware(['verifyMembership']);
Route::post('/calculators/sip-calculator/limited-period-sip-calculator-output', 'Frontend\Calculators\FutureValueLimitedPeriodSIPController@output')->name('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_output')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/limited-period-sip-calculator-pdf', 'Frontend\Calculators\FutureValueLimitedPeriodSIPController@pdf')->name('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/limited-period-sip-calculator-save', 'Frontend\Calculators\FutureValueLimitedPeriodSIPController@save')->name('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_save')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/limited-period-sip-calculator-back', 'Frontend\Calculators\FutureValueLimitedPeriodSIPController@index')->name('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_back')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/limited-period-sip-calculator-view', 'Frontend\Calculators\FutureValueLimitedPeriodSIPController@view')->name('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_view')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/limited-period-sip-calculator-edit', 'Frontend\Calculators\FutureValueLimitedPeriodSIPController@edit')->name('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_edit')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/limited-period-sip-calculator-merge-download', 'Frontend\Calculators\FutureValueLimitedPeriodSIPController@merge_download')->name('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_merge_download')->middleware(['verifyMembership']);

// future-value-of-one-time-investment
Route::get('/calculators/onetime-investment/future-value-of-one-time-investment', 'Frontend\Calculators\FutureValueOfLumpSumInvestmentController@index')->name('frontend.futureValueOfLumpsumInvestment')->middleware(['verifyMembership']);
Route::post('/calculators/onetime-investment/future-value-of-one-time-investment-output', 'Frontend\Calculators\FutureValueOfLumpSumInvestmentController@output')->name('frontend.futureValueOfLumpsumInvestment_output')->middleware(['verifyMembership']);
Route::get('/calculators/onetime-investment/future-value-of-one-time-investment-pdf', 'Frontend\Calculators\FutureValueOfLumpSumInvestmentController@pdf')->name('frontend.futureValueOfLumpsumInvestment_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/onetime-investment/future-value-of-one-time-investment-save', 'Frontend\Calculators\FutureValueOfLumpSumInvestmentController@save')->name('frontend.futureValueOfLumpsumInvestment_save')->middleware(['verifyMembership']);
Route::get('/calculators/onetime-investment/future-value-of-one-time-investment-back', 'Frontend\Calculators\FutureValueOfLumpSumInvestmentController@index')->name('frontend.futureValueOfLumpsumInvestment_back')->middleware(['verifyMembership']);
Route::get('/calculators/onetime-investment/future-value-of-one-time-investment-view', 'Frontend\Calculators\FutureValueOfLumpSumInvestmentController@view')->name('frontend.futureValueOfLumpsumInvestment_view')->middleware(['verifyMembership']);
Route::get('/calculators/onetime-investment/future-value-of-one-time-investment-edit', 'Frontend\Calculators\FutureValueOfLumpSumInvestmentController@edit')->name('frontend.futureValueOfLumpsumInvestment_edit')->middleware(['verifyMembership']);
Route::get('/calculators/onetime-investment/future-value-of-one-time-investment-merge-download', 'Frontend\Calculators\FutureValueOfLumpSumInvestmentController@merge_download')->name('frontend.futureValueOfLumpsumInvestment_merge_download')->middleware(['verifyMembership']);

// future-value-of-annual-lumpsum-investment
Route::get('/calculators/onetime-investment/future-value-of-annual-lumpsum-investment', 'Frontend\Calculators\FutureValueOfAnnualLumpSumInvestmentController@index')->name('frontend.futureValueOfAnnualLumpsumInvestment')->middleware(['verifyMembership']);

Route::post('/calculators/onetime-investment/future-value-of-annual-lumpsum-investment-output', 'Frontend\Calculators\FutureValueOfAnnualLumpSumInvestmentController@output')->name('frontend.futureValueOfAnnualLumpsumInvestment_output')->middleware(['verifyMembership']);

Route::get('/calculators/onetime-investment/future-value-of-annual-lumpsum-investment-pdf', 'Frontend\Calculators\FutureValueOfAnnualLumpSumInvestmentController@pdf')->name('frontend.futureValueOfAnnualLumpsumInvestment_pdf')->middleware(['verifyMembership']);

Route::get('/calculators/onetime-investment/future-value-of-annual-lumpsum-investment-save', 'Frontend\Calculators\FutureValueOfAnnualLumpSumInvestmentController@save')->name('frontend.futureValueOfAnnualLumpsumInvestment_save')->middleware(['verifyMembership']);

Route::get('/calculators/onetime-investment/future-value-of-annual-lumpsum-investment-back', 'Frontend\Calculators\FutureValueOfAnnualLumpSumInvestmentController@index')->name('frontend.futureValueOfAnnualLumpsumInvestment_back')->middleware(['verifyMembership']);

Route::get('/calculators/onetime-investment/future-value-of-annual-lumpsum-investment-view', 'Frontend\Calculators\FutureValueOfAnnualLumpSumInvestmentController@view')->name('frontend.futureValueOfAnnualLumpsumInvestment_view')->middleware(['verifyMembership']);

Route::get('/calculators/onetime-investment/future-value-of-annual-lumpsum-investment-edit', 'Frontend\Calculators\FutureValueOfAnnualLumpSumInvestmentController@edit')->name('frontend.futureValueOfAnnualLumpsumInvestment_edit')->middleware(['verifyMembership']);

Route::get('/calculators/onetime-investment/future-value-of-annual-lumpsum-investment-merge-download', 'Frontend\Calculators\FutureValueOfAnnualLumpSumInvestmentController@merge_download')->name('frontend.futureValueOfAnnualLumpsumInvestment_merge_download')->middleware(['verifyMembership']);


//Sourav Changes
Route::get('/calculators/sip-calculator/future-value-of-sip', 'Frontend\Calculators\FutureValueSipController@futureValueOfSipIndex')->name('frontend.futureValueOfSipIndex')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-sip-output-back', 'Frontend\Calculators\FutureValueSipController@futureValueOfSipIndex')->name('frontend.futureValueOfSipBack')->middleware(['verifyMembership']);
Route::post('/calculators/sip-calculator/future-value-of-sip-output', 'Frontend\Calculators\FutureValueSipController@futureValueOfSipOutput')->name('frontend.futureValueOfSipOutput')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-sip-output', 'Frontend\Calculators\FutureValueSipController@futureValueOfSipOutput')->name('frontend.futureValueOfSipOutput')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-sip-output-pdf', 'Frontend\Calculators\FutureValueSipController@futureValueOfSipOutputPdfDownload')->name('frontend.futureValueOfSipOutputPdfDownload')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-sip-view', 'Frontend\Calculators\FutureValueSipController@view')->name('frontend.futureValueOfSipOutputView')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-sip-edit', 'Frontend\Calculators\FutureValueSipController@edit')->name('frontend.futureValueOfSipOutputEdit')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-sip-merge-download', 'Frontend\Calculators\FutureValueSipController@merge_download')->name('frontend.futureValueOfSipOutputMergeDownload')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-sip-output-save', 'Frontend\Calculators\FutureValueSipController@futureValueOfSipOutputSave')->name('frontend.futureValueOfSipOutputSave')->middleware(['verifyMembership']);

Route::get('/calculators/sip-calculator/limited-period-sip-need-based-calculator', 'Frontend\Calculators\LimitedPeriodSipGoalPlanningCalculatorController@limitedPeriodSIPgoalPlanningCalculator')->name('frontend.limitedPeriodSIPgoalPlanningCalculator')->middleware(['verifyMembership']);
Route::post('/calculators/sip-calculator/limited-period-sip-need-based-calculator-output', 'Frontend\Calculators\LimitedPeriodSipGoalPlanningCalculatorController@limitedPeriodSIPgoalPlanningCalculatorOutput')->name('frontend.limitedPeriodSIPgoalPlanningCalculatorOutput')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/limited-period-sip-need-based-calculator-output', 'Frontend\Calculators\LimitedPeriodSipGoalPlanningCalculatorController@limitedPeriodSIPgoalPlanningCalculatorOutput')->name('frontend.limitedPeriodSIPgoalPlanningCalculatorOutput')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/limited-period-sip-need-based-calculator-output-pdf', 'Frontend\Calculators\LimitedPeriodSipGoalPlanningCalculatorController@limitedPeriodSIPgoalPlanningCalculatorOutputPdfDownload')->name('frontend.limitedPeriodSIPgoalPlanningCalculatorOutputPdfDownload')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/limited-period-sip-need-based-calculator-output-save', 'Frontend\Calculators\LimitedPeriodSipGoalPlanningCalculatorController@limitedPeriodSIPgoalPlanningCalculatorOutputSave')->name('frontend.limitedPeriodSIPgoalPlanningCalculatorOutputSave')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/limited-period-sip-need-based-calculator-output-back', 'Frontend\Calculators\LimitedPeriodSipGoalPlanningCalculatorController@limitedPeriodSIPgoalPlanningCalculator')->name('frontend.limitedPeriodSIPgoalPlanningCalculatorBack')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/limited-period-sip-need-based-calculator-edit', 'Frontend\Calculators\LimitedPeriodSipGoalPlanningCalculatorController@limitedPeriodSIPgoalPlanningCalculatorEdit')->name('frontend.limitedPeriodSIPgoalPlanningCalculatorEdit')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/limited-period-sip-need-based-calculator-view', 'Frontend\Calculators\LimitedPeriodSipGoalPlanningCalculatorController@limitedPeriodSIPgoalPlanningCalculatorView')->name('frontend.limitedPeriodSIPgoalPlanningCalculatorView')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/limited-period-sip-need-based-calculator-merge-download', 'Frontend\Calculators\LimitedPeriodSipGoalPlanningCalculatorController@merge_download')->name('frontend.limitedPeriodSIPgoalPlanningCalculatorMergeDownload')->middleware(['verifyMembership']);

Route::get('/premium-calculator/portfolio_projection', 'Frontend\Calculators\PremiumController@portfolio_projection')->name('frontend.portfolio_projection')->middleware(['verifyMembership']);
Route::post('/premium-calculator/portfolio_projection_output', 'Frontend\Calculators\PremiumController@portfolio_projection_output')->name('frontend.portfolio_projection_output')->middleware(['verifyMembership']);
Route::get('/premium-calculator/portfolio_projection_output_save', 'Frontend\Calculators\PremiumController@portfolio_projection_output_save')->name('frontend.portfolio_projection_output_save')->middleware(['verifyMembership']);
Route::get('/premium-calculator/portfolio_projection_output_pdf', 'Frontend\Calculators\PremiumController@portfolio_projection_output_pdf')->name('frontend.portfolio_projection_output_pdf')->middleware(['verifyMembership']);
Route::get('/premium-calculator/portfolio_projection-edit', 'Frontend\Calculators\PremiumController@edit')->name('frontend.portfolio_projection_edit')->middleware(['verifyMembership']);
Route::get('/premium-calculator/portfolio_projection-view', 'Frontend\Calculators\PremiumController@view')->name('frontend.portfolio_projection_view')->middleware(['verifyMembership']);
Route::get('/premium-calculator/portfolio_projection-back', 'Frontend\Calculators\PremiumController@portfolio_projection')->name('frontend.portfolio_projection_back')->middleware(['verifyMembership']);
Route::get('/premium-calculator/portfolio_projection-merge-download', 'Frontend\Calculators\PremiumController@merge_download')->name('frontend.portfolio_projection_MergeDownload')->middleware(['verifyMembership']);

Route::get('/calculators/sip-calculator/sip-future-value-ready-recokner', 'Frontend\Calculators\SipFutureValueReadyRecoknerContrtoller@sip_future_value_ready_reckoner')->name('frontend.sipFutureValueReadyReckoner')->middleware(['verifyMembership']);
Route::post('/calculators/sip-calculator/sip-future-value-ready-recokner-output', 'Frontend\Calculators\SipFutureValueReadyRecoknerContrtoller@sip_future_value_ready_reckoner_output')->name('frontend.sipFutureValueReadyReckonerOutput')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-future-value-ready-recokner-output', 'Frontend\Calculators\SipFutureValueReadyRecoknerContrtoller@sip_future_value_ready_reckoner_output')->name('frontend.sipFutureValueReadyReckonerOutput')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-future-value-ready-recokner-output-pdf', 'Frontend\Calculators\SipFutureValueReadyRecoknerContrtoller@sip_future_value_ready_reckoner_output_pdf_download')->name('frontend.sipFutureValueReadyReckonerOutputPdf')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-future-value-ready-recokner-output-save', 'Frontend\Calculators\SipFutureValueReadyRecoknerContrtoller@sip_future_value_ready_reckoner_output_save')->name('frontend.sipFutureValueReadyReckonerOutputSave')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-future-value-ready-recokner-back', 'Frontend\Calculators\SipFutureValueReadyRecoknerContrtoller@sip_future_value_ready_reckoner')->name('frontend.sipFutureValueReadyReckonerBack')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-future-value-ready-recokner-view', 'Frontend\Calculators\SipFutureValueReadyRecoknerContrtoller@view')->name('frontend.sipFutureValueReadyReckonerView')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-future-value-ready-recokner-edit', 'Frontend\Calculators\SipFutureValueReadyRecoknerContrtoller@sip_future_value_ready_reckoner_edit')->name('frontend.sipFutureValueReadyReckoneredit')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-future-value-ready-recokner-merge-download', 'Frontend\Calculators\SipFutureValueReadyRecoknerContrtoller@merge_download')->name('frontend.sipFutureValueReadyReckonerMergeDownload')->middleware(['verifyMembership']);

Route::get('/calculators/sip-calculator/sip-need-based-ready-reckoner', 'Frontend\Calculators\SipNeedBasedReadyReckonerController@sip_need_based_ready_reckoner')->name('frontend.sipNeedBasedReadyReckoner')->middleware(['verifyMembership']);
Route::post('/calculators/sip-calculator/sip-need-based-ready-reckoner-output', 'Frontend\Calculators\SipNeedBasedReadyReckonerController@sip_need_based_ready_reckoner_output')->name('frontend.sipNeedBasedReadyReckonerOutput')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-need-based-ready-reckoner-output', 'Frontend\Calculators\SipNeedBasedReadyReckonerController@sip_need_based_ready_reckoner_output')->name('frontend.sipNeedBasedReadyReckonerOutput')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-need-based-ready-reckoner-output-pdf', 'Frontend\Calculators\SipNeedBasedReadyReckonerController@sip_need_based_ready_reckoner_output_pdf_download')->name('frontend.sipNeedBasedReadyReckonerOutputPdf')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-need-based-ready-reckoner-output-save', 'Frontend\Calculators\SipNeedBasedReadyReckonerController@sip_need_based_ready_reckoner_output_save')->name('frontend.sipNeedBasedReadyReckonerOutputSave')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-need-based-ready-reckoner-back', 'Frontend\Calculators\SipNeedBasedReadyReckonerController@sip_need_based_ready_reckoner')->name('frontend.sipNeedBasedReadyReckonerBack')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-need-based-ready-reckoner-view', 'Frontend\Calculators\SipNeedBasedReadyReckonerController@view')->name('frontend.sipNeedBasedReadyReckonerView')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-need-based-ready-reckoner-edit', 'Frontend\Calculators\SipNeedBasedReadyReckonerController@sip_need_based_ready_reckoner_edit')->name('frontend.sipNeedBasedReadyReckoneredit')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-need-based-ready-reckoner-merge-download', 'Frontend\Calculators\SipNeedBasedReadyReckonerController@merge_download')->name('frontend.sipNeedBasedReadyReckonerMergeDownload')->middleware(['verifyMembership']);
//Sourav Ends

// sip-required-for-target-future-value
Route::get('/calculators/sip-calculator/sip-required-for-target-future-value', 'Frontend\Calculators\SipRequiredForTargetFutureValueController@index')->name('frontend.sipRequiredForTargetFutureValue')->middleware(['verifyMembership']);
Route::post('/calculators/sip-calculator/sip-required-for-target-future-value-output', 'Frontend\Calculators\SipRequiredForTargetFutureValueController@output')->name('frontend.sipRequiredForTargetFutureValue_output')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-required-for-target-future-value-pdf', 'Frontend\Calculators\SipRequiredForTargetFutureValueController@pdf')->name('frontend.sipRequiredForTargetFutureValue_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-required-for-target-future-value-save', 'Frontend\Calculators\SipRequiredForTargetFutureValueController@save')->name('frontend.sipRequiredForTargetFutureValue_save')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-required-for-target-future-value-back', 'Frontend\Calculators\SipRequiredForTargetFutureValueController@index')->name('frontend.sipRequiredForTargetFutureValue_back')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-required-for-target-future-value-view', 'Frontend\Calculators\SipRequiredForTargetFutureValueController@view')->name('frontend.sipRequiredForTargetFutureValue_view')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-required-for-target-future-value-edit', 'Frontend\Calculators\SipRequiredForTargetFutureValueController@edit')->name('frontend.sipRequiredForTargetFutureValue_edit')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/sip-required-for-target-future-value-merge-download', 'Frontend\Calculators\SipRequiredForTargetFutureValueController@merge_download')->name('frontend.sipRequiredForTargetFutureValue_merge_download')->middleware(['verifyMembership']);


// capital_gains_tax_calculator
Route::get('/premium-calculator/capital_gains_tax_calculator', 'Frontend\Calculators\CapitalGainsTaxCalcController@index')->name('frontend.capital_gains_tax_calculator')->middleware(['verifyMembership']);
Route::post('/premium-calculator/capital_gains_tax_calculator-output', 'Frontend\Calculators\CapitalGainsTaxCalcController@output')->name('frontend.capital_gains_tax_calculator_output')->middleware(['verifyMembership']);
Route::get('/premium-calculator/capital_gains_tax_calculator-pdf', 'Frontend\Calculators\CapitalGainsTaxCalcController@pdf')->name('frontend.capital_gains_tax_calculator_pdf')->middleware(['verifyMembership']);
Route::get('/premium-calculator/capital_gains_tax_calculator-save', 'Frontend\Calculators\CapitalGainsTaxCalcController@save')->name('frontend.capital_gains_tax_calculator_save')->middleware(['verifyMembership']);
Route::get('/premium-calculator/capital_gains_tax_calculator-back', 'Frontend\Calculators\CapitalGainsTaxCalcController@index')->name('frontend.capital_gains_tax_calculator_back')->middleware(['verifyMembership']);
Route::get('/premium-calculator/capital_gains_tax_calculator-view', 'Frontend\Calculators\CapitalGainsTaxCalcController@view')->name('frontend.capital_gains_tax_calculator_view')->middleware(['verifyMembership']);
Route::get('/premium-calculator/capital_gains_tax_calculator-edit', 'Frontend\Calculators\CapitalGainsTaxCalcController@edit')->name('frontend.capital_gains_tax_calculator_edit')->middleware(['verifyMembership']);
Route::get('/premium-calculator/capital_gains_tax_calculator-merge-download', 'Frontend\Calculators\CapitalGainsTaxCalcController@merge_download')->name('frontend.capital_gains_tax_calculator_merge_download')->middleware(['verifyMembership']);

// monthly-annuity-for-lumpsum-investment
Route::get('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment', 'Frontend\Calculators\MonthlyAnnForLumpsumController@index')->name('frontend.monthlyAnnuityForLumpsumInvestment')->middleware(['verifyMembership']);
Route::post('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment-output', 'Frontend\Calculators\MonthlyAnnForLumpsumController@output')->name('frontend.monthlyAnnuityForLumpsumInvestment_output')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment-pdf', 'Frontend\Calculators\MonthlyAnnForLumpsumController@pdf')->name('frontend.monthlyAnnuityForLumpsumInvestment_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment-save', 'Frontend\Calculators\MonthlyAnnForLumpsumController@save')->name('frontend.monthlyAnnuityForLumpsumInvestment_save')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment-back', 'Frontend\Calculators\MonthlyAnnForLumpsumController@index')->name('frontend.monthlyAnnuityForLumpsumInvestment_back')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment-view', 'Frontend\Calculators\MonthlyAnnForLumpsumController@view')->name('frontend.monthlyAnnuityForLumpsumInvestment_view')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment-edit', 'Frontend\Calculators\MonthlyAnnForLumpsumController@edit')->name('frontend.monthlyAnnuityForLumpsumInvestment_edit')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-lumpsum-investment-merge-download', 'Frontend\Calculators\MonthlyAnnForLumpsumController@merge_download')->name('frontend.monthlyAnnuityForLumpsumInvestment_merge_download')->middleware(['verifyMembership']);

// retirement-need-based-calculator
Route::get('/calculators/need-based-calculator/retirement-need-based-calculator', 'Frontend\Calculators\RetirementNeedBasedCalcController@index')->name('frontend.retirementPlanning')->middleware(['verifyMembership']);
Route::post('/calculators/need-based-calculator/retirement-need-based-calculator-output', 'Frontend\Calculators\RetirementNeedBasedCalcController@output')->name('frontend.retirementPlanning_output')->middleware(['verifyMembership']);
Route::get('/calculators/need-based-calculator/retirement-need-based-calculator-pdf', 'Frontend\Calculators\RetirementNeedBasedCalcController@pdf')->name('frontend.retirementPlanning_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/need-based-calculator/retirement-need-based-calculator-save', 'Frontend\Calculators\RetirementNeedBasedCalcController@save')->name('frontend.retirementPlanning_save')->middleware(['verifyMembership']);
Route::get('/calculators/need-based-calculator/retirement-need-based-calculator-back', 'Frontend\Calculators\RetirementNeedBasedCalcController@index')->name('frontend.retirementPlanning_back')->middleware(['verifyMembership']);
Route::get('/calculators/need-based-calculator/retirement-need-based-calculator-view', 'Frontend\Calculators\RetirementNeedBasedCalcController@view')->name('frontend.retirementPlanning_view')->middleware(['verifyMembership']);
Route::get('/calculators/need-based-calculator/retirement-need-based-calculator-edit', 'Frontend\Calculators\RetirementNeedBasedCalcController@edit')->name('frontend.retirementPlanning_edit')->middleware(['verifyMembership']);
Route::get('/calculators/need-based-calculator/retirement-need-based-calculator-merge-download', 'Frontend\Calculators\RetirementNeedBasedCalcController@merge_download')->name('frontend.retirementPlanning_merge_download')->middleware(['verifyMembership']);


// sip-or-stp-required-for-target-future-value
Route::get('/calculators/combination/sip-or-stp-required-for-target-future-value', 'Frontend\Calculators\SipStpRquiredForTargetFutureController@index')->name('frontend.sipStpRequiredForTargetFutureValue')->middleware(['verifyMembership']);
Route::post('/calculators/combination/sip-or-stp-required-for-target-future-value-output', 'Frontend\Calculators\SipStpRquiredForTargetFutureController@output')->name('frontend.sipStpRequiredForTargetFutureValue_output')->middleware(['verifyMembership']);
Route::get('/calculators/combination/sip-or-stp-required-for-target-future-value-pdf', 'Frontend\Calculators\SipStpRquiredForTargetFutureController@pdf')->name('frontend.sipStpRequiredForTargetFutureValue_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/combination/sip-or-stp-required-for-target-future-value-save', 'Frontend\Calculators\SipStpRquiredForTargetFutureController@save')->name('frontend.sipStpRequiredForTargetFutureValue_save')->middleware(['verifyMembership']);
Route::get('/calculators/combination/sip-or-stp-required-for-target-future-value-back', 'Frontend\Calculators\SipStpRquiredForTargetFutureController@index')->name('frontend.sipStpRequiredForTargetFutureValue_back')->middleware(['verifyMembership']);
Route::get('/calculators/combination/sip-or-stp-required-for-target-future-value-view', 'Frontend\Calculators\SipStpRquiredForTargetFutureController@view')->name('frontend.sipStpRequiredForTargetFutureValue_view')->middleware(['verifyMembership']);
Route::get('/calculators/combination/sip-or-stp-required-for-target-future-value-edit', 'Frontend\Calculators\SipStpRquiredForTargetFutureController@edit')->name('frontend.sipStpRequiredForTargetFutureValue_edit')->middleware(['verifyMembership']);
Route::get('/calculators/combination/sip-or-stp-required-for-target-future-value-merge-download', 'Frontend\Calculators\SipStpRquiredForTargetFutureController@merge_download')->name('frontend.sipStpRequiredForTargetFutureValue_merge_download')->middleware(['verifyMembership']);


// hlv_calculation
Route::get('/premium-calculator/hlv_calculation', 'Frontend\Calculators\HlvCalculationController@index')->name('frontend.hlv_calculation')->middleware(['verifyMembership']);
Route::post('/premium-calculator/hlv_calculation-output', 'Frontend\Calculators\HlvCalculationController@output')->name('frontend.hlv_calculation_output')->middleware(['verifyMembership']);
Route::get('/premium-calculator/hlv_calculation-pdf', 'Frontend\Calculators\HlvCalculationController@pdf')->name('frontend.hlv_calculation_pdf')->middleware(['verifyMembership']);
Route::get('/premium-calculator/hlv_calculation-save', 'Frontend\Calculators\HlvCalculationController@save')->name('frontend.hlv_calculation_save')->middleware(['verifyMembership']);
Route::get('/premium-calculator/hlv_calculation-back', 'Frontend\Calculators\HlvCalculationController@index')->name('frontend.hlv_calculation_back')->middleware(['verifyMembership']);
Route::get('/premium-calculator/hlv_calculation-view', 'Frontend\Calculators\HlvCalculationController@view')->name('frontend.hlv_calculation_view')->middleware(['verifyMembership']);
Route::get('/premium-calculator/hlv_calculation-edit', 'Frontend\Calculators\HlvCalculationController@edit')->name('frontend.hlv_calculation_edit')->middleware(['verifyMembership']);
Route::get('/premium-calculator/hlv_calculation-merge-download', 'Frontend\Calculators\HlvCalculationController@merge_download')->name('frontend.hlv_calculation_merge_download')->middleware(['verifyMembership']);

// future-value-of-sip-stp
Route::get('/calculators/combination/future-value-of-sip-stp', 'Frontend\Calculators\FutureValueOfSipStpController@index')->name('frontend.futureValueOfSipStp')->middleware(['verifyMembership']);
Route::post('/calculators/combination/future-value-of-sip-stp-output', 'Frontend\Calculators\FutureValueOfSipStpController@output')->name('frontend.futureValueOfSipStp_output')->middleware(['verifyMembership']);
Route::get('/calculators/combination/future-value-of-sip-stp-pdf', 'Frontend\Calculators\FutureValueOfSipStpController@pdf')->name('frontend.futureValueOfSipStp_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/combination/future-value-of-sip-stp-save', 'Frontend\Calculators\FutureValueOfSipStpController@save')->name('frontend.futureValueOfSipStp_save')->middleware(['verifyMembership']);
Route::get('/calculators/combination/future-value-of-sip-stp-back', 'Frontend\Calculators\FutureValueOfSipStpController@index')->name('frontend.futureValueOfSipStp_back')->middleware(['verifyMembership']);
Route::get('/calculators/combination/future-value-of-sip-stp-view', 'Frontend\Calculators\FutureValueOfSipStpController@view')->name('frontend.futureValueOfSipStp_view')->middleware(['verifyMembership']);
Route::get('/calculators/combination/future-value-of-sip-stp-edit', 'Frontend\Calculators\FutureValueOfSipStpController@edit')->name('frontend.futureValueOfSipStp_edit')->middleware(['verifyMembership']);
Route::get('/calculators/combination/future-value-of-sip-stp-merge-download', 'Frontend\Calculators\FutureValueOfSipStpController@merge_download')->name('frontend.futureValueOfSipStp_merge_download')->middleware(['verifyMembership']);


// term-insurance-sip
Route::get('/calculators/mf-vs-insurance/term-insurance-sip', 'Frontend\Calculators\TermInsuranceSIPController@index')->name('frontend.termInsuranceSIP')->middleware(['verifyMembership']);
Route::post('/calculators/mf-vs-insurance/term-insurance-sip-output', 'Frontend\Calculators\TermInsuranceSIPController@output')->name('frontend.termInsuranceSIP_output')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/term-insurance-sip-pdf', 'Frontend\Calculators\TermInsuranceSIPController@pdf')->name('frontend.termInsuranceSIP_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/term-insurance-sip-save', 'Frontend\Calculators\TermInsuranceSIPController@save')->name('frontend.termInsuranceSIP_save')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/term-insurance-sip-back', 'Frontend\Calculators\TermInsuranceSIPController@index')->name('frontend.termInsuranceSIP_back')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/term-insurance-sip-view', 'Frontend\Calculators\TermInsuranceSIPController@view')->name('frontend.termInsuranceSIP_view')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/term-insurance-sip-edit', 'Frontend\Calculators\TermInsuranceSIPController@edit')->name('frontend.termInsuranceSIP_edit')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/term-insurance-sip-merge-download', 'Frontend\Calculators\TermInsuranceSIPController@merge_download')->name('frontend.termInsuranceSIP_merge_download')->middleware(['verifyMembership']);

//Sourav 2 routes
Route::get('/calculators/swp-calculator/monthly-annuity-for-sip', 'Frontend\Calculators\SwpCalculatorMonthlyAnnuityForSipController@monthlyAnnuityForSIP')->name('frontend.monthlyAnnuityForSIP')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-sip-output', 'Frontend\Calculators\SwpCalculatorMonthlyAnnuityForSipController@monthlyAnnuityForSIPOUTPUT')->name('frontend.monthlyAnnuityForSIPOUTPUT')->middleware(['verifyMembership']);
Route::post('/calculators/swp-calculator/monthly-annuity-for-sip-output', 'Frontend\Calculators\SwpCalculatorMonthlyAnnuityForSipController@monthlyAnnuityForSIPOUTPUT')->name('frontend.monthlyAnnuityForSIPOUTPUT')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-sip-output-pdf', 'Frontend\Calculators\SwpCalculatorMonthlyAnnuityForSipController@monthlyAnnuityForSIPOutputDownloadPdf')->name('frontend.monthlyAnnuityForSIPOutputDownloadPdf')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-sip-output-save', 'Frontend\Calculators\SwpCalculatorMonthlyAnnuityForSipController@monthlyAnnuityForSIPOutputSave')->name('frontend.monthlyAnnuityForSIPOutputSave')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-sip-back', 'Frontend\Calculators\SwpCalculatorMonthlyAnnuityForSipController@monthlyAnnuityForSIP')->name('frontend.monthlyAnnuityForBack')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-sip-view', 'Frontend\Calculators\SwpCalculatorMonthlyAnnuityForSipController@view')->name('ffrontend.monthlyAnnuityForView')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-sip-edit', 'Frontend\Calculators\SwpCalculatorMonthlyAnnuityForSipController@edit')->name('frontend.monthlyAnnuityForedit')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/monthly-annuity-for-sip-merge-download', 'Frontend\Calculators\SwpCalculatorMonthlyAnnuityForSipController@merge_download')->name('frontend.monthlyAnnuityForMergeDownload')->middleware(['verifyMembership']);
	

Route::get('/calculators/swp-calculator/sip-required-for-target-monthly-annuity', 'Frontend\Calculators\SipRequiredTargetMonthlyAnnuityController@monthlyAnnuityForSIP')->name('frontend.monthlyTargetAnnuityForSIP')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-output', 'Frontend\Calculators\SipRequiredTargetMonthlyAnnuityController@monthlyAnnuityForSIPOUTPUT')->name('frontend.monthlyTargetAnnuityForSIPOUTPUT')->middleware(['verifyMembership']);
Route::post('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-output', 'Frontend\Calculators\SipRequiredTargetMonthlyAnnuityController@monthlyAnnuityForSIPOUTPUT')->name('frontend.monthlyTargetAnnuityForSIPOUTPUT')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-output-pdf', 'Frontend\Calculators\SipRequiredTargetMonthlyAnnuityController@monthlyAnnuityForSIPOutputDownloadPdf')->name('frontend.monthlyTargetAnnuityForSIPOutputDownloadPdf')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-output-save', 'Frontend\Calculators\SipRequiredTargetMonthlyAnnuityController@monthlyAnnuityForSIPOutputSave')->name('frontend.monthlyTargetAnnuityForSIPOutputSave')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-back', 'Frontend\Calculators\SipRequiredTargetMonthlyAnnuityController@monthlyAnnuityForSIP')->name('frontend.monthlyTargetAnnuityForBack')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-view', 'Frontend\Calculators\SipRequiredTargetMonthlyAnnuityController@view')->name('frontend.monthlyTargetAnnuityForView')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-edit', 'Frontend\Calculators\SipRequiredTargetMonthlyAnnuityController@edit')->name('frontend.monthlyTargetAnnuityForedit')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/sip-required-for-target-monthly-annuity-merge-download', 'Frontend\Calculators\SipRequiredTargetMonthlyAnnuityController@merge_download')->name('frontend.monthlyTargetAnnuityForMergeDownload')->middleware(['verifyMembership']);


Route::get('/calculators/combination/sip-or-lumpsum-investment-required-for-target-future-value', 'Frontend\Calculators\SipLumpsumInvestmentRequiredForTargetFutureValueController@sipLumpsumInvestmentTargetFutureValue')->name('frontend.sipLumpsumInvestmentTargetFutureValue')->middleware(['verifyMembership']);
Route::get('/calculators/combination/sip-or-lumpsum-investment-required-for-target-future-value-output', 'Frontend\Calculators\SipLumpsumInvestmentRequiredForTargetFutureValueController@sipLumpsumInvestmentTargetFutureValueOutput')->name('frontend.sipLumpsumInvestmentTargetFutureValueOutput')->middleware(['verifyMembership']);
Route::post('/calculators/combination/sip-or-lumpsum-investment-required-for-target-future-value-output', 'Frontend\Calculators\SipLumpsumInvestmentRequiredForTargetFutureValueController@sipLumpsumInvestmentTargetFutureValueOutput')->name('frontend.sipLumpsumInvestmentTargetFutureValueOutput')->middleware(['verifyMembership']);
Route::get('/calculators/combination/sip-or-lumpsum-investment-required-for-target-future-value-output-pdf', 'Frontend\Calculators\SipLumpsumInvestmentRequiredForTargetFutureValueController@sipLumpsumInvestmentTargetFutureValueOutputPdfDownload')->name('frontend.sipLumpsumInvestmentTargetFutureValueOutputPdfDownload')->middleware(['verifyMembership']);
Route::get('/calculators/combination/sip-or-lumpsum-investment-required-for-target-future-value-output-save', 'Frontend\Calculators\SipLumpsumInvestmentRequiredForTargetFutureValueController@sipLumpsumInvestmentTargetFutureValueOutputSave')->name('frontend.sipLumpsumInvestmentTargetFutureValueOutputSave')->middleware(['verifyMembership']);
Route::get('/calculators/combination/sip-or-lumpsum-investment-required-for-target-future-value-back', 'Frontend\Calculators\SipLumpsumInvestmentRequiredForTargetFutureValueController@sipLumpsumInvestmentTargetFutureValue')->name('frontend.sipLumpsumInvestmentTargetFutureValueBack')->middleware(['verifyMembership']);
Route::get('/calculators/combination/sip-or-lumpsum-investment-required-for-target-future-value-view', 'Frontend\Calculators\SipLumpsumInvestmentRequiredForTargetFutureValueController@view')->name('frontend.sipLumpsumInvestmentTargetFutureValueView')->middleware(['verifyMembership']);
Route::get('/calculators/combination/sip-or-lumpsum-investment-required-for-target-future-value-edit', 'Frontend\Calculators\SipLumpsumInvestmentRequiredForTargetFutureValueController@edit')->name('frontend.sipLumpsumInvestmentTargetFutureValueedit')->middleware(['verifyMembership']);
Route::get('/calculators/combination/sip-or-lumpsum-investment-required-for-target-future-value-merge-download', 'Frontend\Calculators\SipLumpsumInvestmentRequiredForTargetFutureValueController@merge_download')->name('frontend.sipLumpsumInvestmentTargetFutureValueMergeDownload')->middleware(['verifyMembership']);


Route::get('/calculators/combination/future-value-of-lumpsum-sip', 'Frontend\Calculators\FutureValueLumpsumSipController@futureValueOfLumpsumSip')->name('frontend.futureValueOfLumpsumSip')->middleware(['verifyMembership']);
Route::get('/calculators/combination/future-value-of-lumpsum-sip-output', 'Frontend\Calculators\FutureValueLumpsumSipController@futureValueOfLumpsumSipOutput')->name('frontend.futureValueOfLumpsumSipOutput')->middleware(['verifyMembership']);
Route::post('/calculators/combination/future-value-of-lumpsum-sip-output', 'Frontend\Calculators\FutureValueLumpsumSipController@futureValueOfLumpsumSipOutput')->name('frontend.futureValueOfLumpsumSipOutput')->middleware(['verifyMembership']);
Route::get('/calculators/combination/future-value-of-lumpsum-sip-output-pdf', 'Frontend\Calculators\FutureValueLumpsumSipController@futureValueOfLumpsumSipOutputPdfDownload')->name('frontend.futureValueOfLumpsumSipOutputPdfDownload')->middleware(['verifyMembership']);
Route::get('/calculators/combination/future-value-of-lumpsum-sip-output-save', 'Frontend\Calculators\FutureValueLumpsumSipController@futureValueOfLumpsumSipOutputSave')->name('frontend.futureValueOfLumpsumSipOutputSave')->middleware(['verifyMembership']);
Route::get('/calculators/combination/future-value-of-lumpsum-sip-back', 'Frontend\Calculators\FutureValueLumpsumSipController@futureValueOfLumpsumSip')->name('frontend.futureValueOfLumpsumSipBack')->middleware(['verifyMembership']);
Route::get('/calculators/combination/future-value-of-lumpsum-sip-view', 'Frontend\Calculators\FutureValueLumpsumSipController@view')->name('frontend.futureValueOfLumpsumSipView')->middleware(['verifyMembership']);
Route::get('/calculators/combination/future-value-of-lumpsum-sip-edit', 'Frontend\Calculators\FutureValueLumpsumSipController@edit')->name('frontend.futureValueOfLumpsumSipedit')->middleware(['verifyMembership']);
Route::get('/calculators/combination/future-value-of-lumpsum-sip-merge-download', 'Frontend\Calculators\FutureValueLumpsumSipController@merge_download')->name('frontend.futureValueOfLumpsumSipMergeDownload')->middleware(['verifyMembership']);


Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-required-target', 'Frontend\Calculators\FutureValueStepUpSipRequiredTargetController@futureValueOfStepUpSIPRequiredTarget')->name('frontend.futureValueOfStepUpSIPRequiredTarget')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-required-target-output', 'Frontend\Calculators\FutureValueStepUpSipRequiredTargetController@futureValueOfStepUpSIPRequiredTargetOutput')->name('frontend.futureValueOfStepUpSIPRequiredTargetOutput')->middleware(['verifyMembership']);
Route::post('/calculators/sip-calculator/future-value-of-stepup-sip-required-target-output', 'Frontend\Calculators\FutureValueStepUpSipRequiredTargetController@futureValueOfStepUpSIPRequiredTargetOutput')->name('frontend.futureValueOfStepUpSIPRequiredTargetOutput')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-required-target-output-pdf', 'Frontend\Calculators\FutureValueStepUpSipRequiredTargetController@futureValueOfStepUpSIPRequiredTargetOutputPdfDownload')->name('frontend.futureValueOfStepUpSIPRequiredTargetOutputPdfDownload')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-required-target-output-save', 'Frontend\Calculators\FutureValueStepUpSipRequiredTargetController@futureValueOfStepUpSIPRequiredTargetOutputSave')->name('frontend.futureValueOfStepUpSIPRequiredTargetOutputSave')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-required-target-back', 'Frontend\Calculators\FutureValueStepUpSipRequiredTargetController@futureValueOfStepUpSIPRequiredTarget')->name('frontend.futureValueOfStepUpSIPRequiredTargetBack')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-required-target-view', 'Frontend\Calculators\FutureValueStepUpSipRequiredTargetController@view')->name('frontend.futureValueOfStepUpSIPRequiredTargetView')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-required-target-edit', 'Frontend\Calculators\FutureValueStepUpSipRequiredTargetController@edit')->name('frontend.futureValueOfStepUpSIPRequiredTargetedit')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-required-target-merge-download', 'Frontend\Calculators\FutureValueStepUpSipRequiredTargetController@merge_download')->name('frontend.futureValueOfStepUpSIPRequiredTargetMergeDownload')->middleware(['verifyMembership']);


Route::get('/calculators/sip-calculator/future-value-of-stepup-sip', 'Frontend\Calculators\FutureValueOfStepUpSipController@futureValueOfStepUpSIP')->name('frontend.futureValueOfStepUpSIP')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-output', 'Frontend\Calculators\FutureValueOfStepUpSipController@futureValueOfStepUpSIPOutput')->name('frontend.futureValueOfStepUpSIPOutput')->middleware(['verifyMembership']);
Route::post('/calculators/sip-calculator/future-value-of-stepup-sip-output', 'Frontend\Calculators\FutureValueOfStepUpSipController@futureValueOfStepUpSIPOutput')->name('frontend.futureValueOfStepUpSIPOutput')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-output-pdf', 'Frontend\Calculators\FutureValueOfStepUpSipController@futureValueOfStepUpSIPOutputPdfDownload')->name('frontend.futureValueOfStepUpSIPOutputPdfDownload')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-output-save', 'Frontend\Calculators\FutureValueOfStepUpSipController@futureValueOfStepUpSIPOutputSave')->name('frontend.futureValueOfStepUpSIPOutputSave')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-back', 'Frontend\Calculators\FutureValueOfStepUpSipController@futureValueOfStepUpSIP')->name('frontend.futureValueOfStepUpSIPBack')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-view', 'Frontend\Calculators\FutureValueOfStepUpSipController@view')->name('frontend.futureValueOfStepUpSIPView')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-edit', 'Frontend\Calculators\FutureValueOfStepUpSipController@edit')->name('frontend.futureValueOfStepUpSIPedit')->middleware(['verifyMembership']);
Route::get('/calculators/sip-calculator/future-value-of-stepup-sip-merge-download', 'Frontend\Calculators\FutureValueOfStepUpSipController@merge_download')->name('frontend.futureValueOfStepUpSIPMergeDownload')->middleware(['verifyMembership']);


Route::get('/calculators/stp-calculator/future-value-of-stp', 'Frontend\Calculators\FutureValueOfStpController@futureValueOfSTP')->name('frontend.futureValueOfSTP')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/future-value-of-stp-output', 'Frontend\Calculators\FutureValueOfStpController@futureValueOfSTPOutput')->name('frontend.futureValueOfSTPOutput')->middleware(['verifyMembership']);
Route::post('/calculators/stp-calculator/future-value-of-stp-output', 'Frontend\Calculators\FutureValueOfStpController@futureValueOfSTPOutput')->name('frontend.futureValueOfSTPOutput')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/future-value-of-stp-output-pdf', 'Frontend\Calculators\FutureValueOfStpController@futureValueOfSTPOutputDownloadPDF')->name('frontend.futureValueOfSTPOutputDownloadPDF')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/future-value-of-stp-output-save', 'Frontend\Calculators\FutureValueOfStpController@futureValueOfSTPOutputSave')->name('frontend.futureValueOfSTPOutputSave')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/future-value-of-stp-back', 'Frontend\Calculators\FutureValueOfStpController@futureValueOfSTP')->name('frontend.futureValueOfSTPBack')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/future-value-of-stp-view', 'Frontend\Calculators\FutureValueOfStpController@view')->name('frontend.futureValueOfSTPView')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/future-value-of-stp-edit', 'Frontend\Calculators\FutureValueOfStpController@edit')->name('frontend.futureValueOfSTPedit')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/future-value-of-stp-merge-download', 'Frontend\Calculators\FutureValueOfStpController@merge_download')->name('frontend.futureValueOfSTPMergeDownload')->middleware(['verifyMembership']);


Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity', 'Frontend\Calculators\LumpsumInvestmentRequiredForTargetMonthlyAnnuityController@lumpsumInvestmentRequiredForTargetMonthlyAnnuity')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuity')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-output', 'Frontend\Calculators\LumpsumInvestmentRequiredForTargetMonthlyAnnuityController@lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutput')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutput')->middleware(['verifyMembership']);
Route::post('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-output', 'Frontend\Calculators\LumpsumInvestmentRequiredForTargetMonthlyAnnuityController@lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutput')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutput')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-output-pdf', 'Frontend\Calculators\LumpsumInvestmentRequiredForTargetMonthlyAnnuityController@lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputDownloadPdf')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputDownloadPdf')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-output-save', 'Frontend\Calculators\LumpsumInvestmentRequiredForTargetMonthlyAnnuityController@lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputSave')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputSave')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-back', 'Frontend\Calculators\LumpsumInvestmentRequiredForTargetMonthlyAnnuityController@lumpsumInvestmentRequiredForTargetMonthlyAnnuity')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityBack')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-view', 'Frontend\Calculators\LumpsumInvestmentRequiredForTargetMonthlyAnnuityController@view')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityView')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-edit', 'Frontend\Calculators\LumpsumInvestmentRequiredForTargetMonthlyAnnuityController@edit')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityedit')->middleware(['verifyMembership']);
Route::get('/calculators/swp-calculator/lumpsum-investment-required-for-target-monthly-annuity-merge-download', 'Frontend\Calculators\LumpsumInvestmentRequiredForTargetMonthlyAnnuityController@merge_download')->name('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityMergeDownload')->middleware(['verifyMembership']);

// sourav 2 routes ends

// term-insurance-sip-goal-base 34
Route::get('/calculators/mf-vs-insurance/term-insurance-sip-goal-base', 'Frontend\Calculators\TermInsuranceSIPgoalBaseCalcController@index')->name('frontend.termInsuranceSIPgoalBase')->middleware(['verifyMembership']);
Route::post('/calculators/mf-vs-insurance/term-insurance-sip-goal-base-output', 'Frontend\Calculators\TermInsuranceSIPgoalBaseCalcController@output')->name('frontend.termInsuranceSIPgoalBase_output')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/term-insurance-sip-goal-base-pdf', 'Frontend\Calculators\TermInsuranceSIPgoalBaseCalcController@pdf')->name('frontend.termInsuranceSIPgoalBase_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/term-insurance-sip-goal-base-save', 'Frontend\Calculators\TermInsuranceSIPgoalBaseCalcController@save')->name('frontend.termInsuranceSIPgoalBase_save')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/term-insurance-sip-goal-base-back', 'Frontend\Calculators\TermInsuranceSIPgoalBaseCalcController@index')->name('frontend.termInsuranceSIPgoalBase_back')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/term-insurance-sip-goal-base-view', 'Frontend\Calculators\TermInsuranceSIPgoalBaseCalcController@view')->name('frontend.termInsuranceSIPgoalBase_view')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/term-insurance-sip-goal-base-edit', 'Frontend\Calculators\TermInsuranceSIPgoalBaseCalcController@edit')->name('frontend.termInsuranceSIPgoalBase_edit')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/term-insurance-sip-goal-base-merge-download', 'Frontend\Calculators\TermInsuranceSIPgoalBaseCalcController@merge_download')->name('frontend.termInsuranceSIPgoalBase_merge_download')->middleware(['verifyMembership']);


// insurance-term-cover 35
Route::get('/calculators/mf-vs-insurance/insurance-term-cover', 'Frontend\Calculators\InsuranceTermCoverCalcController@index')->name('frontend.insuranceTermCover')->middleware(['verifyMembership']);
Route::post('/calculators/mf-vs-insurance/insurance-term-cover-output', 'Frontend\Calculators\InsuranceTermCoverCalcController@output')->name('frontend.insuranceTermCover_output')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/insurance-term-cover-pdf', 'Frontend\Calculators\InsuranceTermCoverCalcController@pdf')->name('frontend.insuranceTermCover_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/insurance-term-cover-save', 'Frontend\Calculators\InsuranceTermCoverCalcController@save')->name('frontend.insuranceTermCover_save')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/insurance-term-cover-back', 'Frontend\Calculators\InsuranceTermCoverCalcController@index')->name('frontend.insuranceTermCover_back')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/insurance-term-cover-view', 'Frontend\Calculators\InsuranceTermCoverCalcController@view')->name('frontend.insuranceTermCover_view')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/insurance-term-cover-edit', 'Frontend\Calculators\InsuranceTermCoverCalcController@edit')->name('frontend.insuranceTermCover_edit')->middleware(['verifyMembership']);
Route::get('/calculators/mf-vs-insurance/insurance-term-cover-merge-download', 'Frontend\Calculators\InsuranceTermCoverCalcController@merge_download')->name('frontend.insuranceTermCover_merge_download')->middleware(['verifyMembership']);


// one-time-investment-need-based-ready-reckoner 36
Route::get('calculators/onetime-investment/one-time-investment-need-based-ready-reckoner', 'Frontend\Calculators\OneTimeInvestmentNeedBasedReadyReckonerController@index')->name('frontend.oneTimeInvestmentGoalPlanningReadyReckoner')->middleware(['verifyMembership']);
Route::post('calculators/onetime-investment/one-time-investment-need-based-ready-reckoner-output', 'Frontend\Calculators\OneTimeInvestmentNeedBasedReadyReckonerController@output')->name('frontend.oneTimeInvestmentGoalPlanningReadyReckoner_output')->middleware(['verifyMembership']);
Route::get('calculators/onetime-investment/one-time-investment-need-based-ready-reckoner-pdf', 'Frontend\Calculators\OneTimeInvestmentNeedBasedReadyReckonerController@pdf')->name('frontend.oneTimeInvestmentGoalPlanningReadyReckoner_pdf')->middleware(['verifyMembership']);
Route::get('calculators/onetime-investment/one-time-investment-need-based-ready-reckoner-save', 'Frontend\Calculators\OneTimeInvestmentNeedBasedReadyReckonerController@save')->name('frontend.oneTimeInvestmentGoalPlanningReadyReckoner_save')->middleware(['verifyMembership']);
Route::get('calculators/onetime-investment/one-time-investment-need-based-ready-reckoner-back', 'Frontend\Calculators\OneTimeInvestmentNeedBasedReadyReckonerController@index')->name('frontend.oneTimeInvestmentGoalPlanningReadyReckoner_back')->middleware(['verifyMembership']);
Route::get('calculators/onetime-investment/one-time-investment-need-based-ready-reckoner-view', 'Frontend\Calculators\OneTimeInvestmentNeedBasedReadyReckonerController@view')->name('frontend.oneTimeInvestmentGoalPlanningReadyReckoner_view')->middleware(['verifyMembership']);
Route::get('calculators/onetime-investment/one-time-investment-need-based-ready-reckoner-edit', 'Frontend\Calculators\OneTimeInvestmentNeedBasedReadyReckonerController@edit')->name('frontend.oneTimeInvestmentGoalPlanningReadyReckoner_edit')->middleware(['verifyMembership']);
Route::get('calculators/onetime-investment/one-time-investment-need-based-ready-reckoner-merge-download', 'Frontend\Calculators\OneTimeInvestmentNeedBasedReadyReckonerController@merge_download')->name('frontend.oneTimeInvestmentGoalPlanningReadyReckoner_merge_download')->middleware(['verifyMembership']);


// stp-required-for-target-future-value 40
Route::get('/calculators/stp-calculator/stp-required-for-target-future-value', 'Frontend\Calculators\StpRequiredForTargetFutureValueController@index')->name('frontend.stpRequiredForTargetFutureValue')->middleware(['verifyMembership']);
Route::post('/calculators/stp-calculator/stp-required-for-target-future-value-output', 'Frontend\Calculators\StpRequiredForTargetFutureValueController@output')->name('frontend.stpRequiredForTargetFutureValue_output')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-required-for-target-future-value-pdf', 'Frontend\Calculators\StpRequiredForTargetFutureValueController@pdf')->name('frontend.stpRequiredForTargetFutureValue_pdf')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-required-for-target-future-value-save', 'Frontend\Calculators\StpRequiredForTargetFutureValueController@save')->name('frontend.stpRequiredForTargetFutureValue_save')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-required-for-target-future-value-back', 'Frontend\Calculators\StpRequiredForTargetFutureValueController@index')->name('frontend.stpRequiredForTargetFutureValue_back')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-required-for-target-future-value-view', 'Frontend\Calculators\StpRequiredForTargetFutureValueController@view')->name('frontend.stpRequiredForTargetFutureValue_view')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-required-for-target-future-value-edit', 'Frontend\Calculators\StpRequiredForTargetFutureValueController@edit')->name('frontend.stpRequiredForTargetFutureValue_edit')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-required-for-target-future-value-merge-download', 'Frontend\Calculators\StpRequiredForTargetFutureValueController@merge_download')->name('frontend.stpRequiredForTargetFutureValue_merge_download')->middleware(['verifyMembership']);


// debt_fund_trade_off 41
Route::get('/premium-calculator/debt_fund_trade_off', 'Frontend\Calculators\DebtFundTradeOffController@index')->name('frontend.debt_fund_trade_off')->middleware(['verifyMembership']);
Route::post('/premium-calculator/debt_fund_trade_off-output', 'Frontend\Calculators\DebtFundTradeOffController@output')->name('frontend.debt_fund_trade_off_output')->middleware(['verifyMembership']);
Route::get('/premium-calculator/debt_fund_trade_off-pdf', 'Frontend\Calculators\DebtFundTradeOffController@pdf')->name('frontend.debt_fund_trade_off_pdf')->middleware(['verifyMembership']);
Route::get('/premium-calculator/debt_fund_trade_off-save', 'Frontend\Calculators\DebtFundTradeOffController@save')->name('frontend.debt_fund_trade_off_save')->middleware(['verifyMembership']);
Route::get('/premium-calculator/debt_fund_trade_off-back', 'Frontend\Calculators\DebtFundTradeOffController@index')->name('frontend.debt_fund_trade_off_back')->middleware(['verifyMembership']);
Route::get('/premium-calculator/debt_fund_trade_off-view', 'Frontend\Calculators\DebtFundTradeOffController@view')->name('frontend.debt_fund_trade_off_view')->middleware(['verifyMembership']);
Route::get('/premium-calculator/debt_fund_trade_off-edit', 'Frontend\Calculators\DebtFundTradeOffController@edit')->name('frontend.debt_fund_trade_off_edit')->middleware(['verifyMembership']);
Route::get('/premium-calculator/debt_fund_trade_off-merge-download', 'Frontend\Calculators\DebtFundTradeOffController@merge_download')->name('frontend.debt_fund_trade_off_merge_download')->middleware(['verifyMembership']);


//Sourav 3
Route::get('/calculators/stp-calculator/stp-future-value-ready-recokner', 'Frontend\Calculators\StpFutureValueReadyRecoknerController@stpFutureValueReadyRecokner')->name('frontend.stpFutureValueReadyRecokner')->middleware(['verifyMembership']);
Route::post('/calculators/stp-calculator/stp-future-value-ready-recokner-output', 'Frontend\Calculators\StpFutureValueReadyRecoknerController@stpFutureValueReadyRecoknerOutput')->name('frontend.stpFutureValueReadyRecoknerOutput')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-future-value-ready-recokner-output', 'Frontend\Calculators\StpFutureValueReadyRecoknerController@stpFutureValueReadyRecoknerOutput')->name('frontend.stpFutureValueReadyRecoknerOutput')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-future-value-ready-recokner-output-pdf', 'Frontend\Calculators\StpFutureValueReadyRecoknerController@stpFutureValueReadyRecoknerOutputDownloadPDF')->name('frontend.stpFutureValueReadyRecoknerOutputDownloadPDF')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-future-value-ready-recokner-output-save', 'Frontend\Calculators\StpFutureValueReadyRecoknerController@stpFutureValueReadyRecoknerOutputSave')->name('frontend.stpFutureValueReadyRecoknerSave')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-future-value-ready-recokner-back', 'Frontend\Calculators\StpFutureValueReadyRecoknerController@stpFutureValueReadyRecokner')->name('frontend.stpFutureValueReadyReckonerBack')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-future-value-ready-recokner-view', 'Frontend\Calculators\StpFutureValueReadyRecoknerController@view')->name('frontend.stpFutureValueReadyReckonerView')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-future-value-ready-recokner-edit', 'Frontend\Calculators\StpFutureValueReadyRecoknerController@edit')->name('frontend.stpFutureValueReadyReckoneredit')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-future-value-ready-recokner-merge-download', 'Frontend\Calculators\StpFutureValueReadyRecoknerController@merge_download')->name('frontend.stpFutureValueReadyReckonerMergeDownload')->middleware(['verifyMembership']);


Route::get('/calculators/stp-calculator/stp-need-based-ready-reckoner', 'Frontend\Calculators\StpNeedBasedReadyReckonerController@stpFutureValueReadyRecokner')->name('frontend.stpGoalPlanningValueReadyRecokner')->middleware(['verifyMembership']);
Route::post('/calculators/stp-calculator/stp-need-based-ready-reckoner-output', 'Frontend\Calculators\StpNeedBasedReadyReckonerController@stpFutureValueReadyRecoknerOutput')->name('frontend.stpGoalPlanningValueReadyRecoknerOutput')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-need-based-ready-reckoner-output', 'Frontend\Calculators\StpNeedBasedReadyReckonerController@stpFutureValueReadyRecoknerOutput')->name('frontend.stpGoalPlanningValueReadyRecoknerOutput')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-need-based-ready-reckoner-output-pdf', 'Frontend\Calculators\StpNeedBasedReadyReckonerController@stpFutureValueReadyRecoknerOutputDownloadPDF')->name('frontend.stpGoalPlanningValueReadyRecoknerOutputDownloadPDF')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-need-based-ready-reckoner-output-save', 'Frontend\Calculators\StpNeedBasedReadyReckonerController@stpFutureValueReadyRecoknerOutputSave')->name('frontend.stpGoalPlanningValueReadyRecoknerOutputSave')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-need-based-ready-reckoner-back', 'Frontend\Calculators\StpNeedBasedReadyReckonerController@stpFutureValueReadyRecokner')->name('frontend.stpGoalPlanningValueReadyRecoknerBack')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-need-based-ready-reckoner-view', 'Frontend\Calculators\StpNeedBasedReadyReckonerController@view')->name('frontend.stpGoalPlanningValueReadyRecoknerView')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-need-based-ready-reckoner-edit', 'Frontend\Calculators\StpNeedBasedReadyReckonerController@edit')->name('frontend.stpGoalPlanningValueReadyRecokneredit')->middleware(['verifyMembership']);
Route::get('/calculators/stp-calculator/stp-need-based-ready-reckoner-merge-download', 'Frontend\Calculators\StpNeedBasedReadyReckonerController@merge_download')->name('frontend.stpGoalPlanningValueReadyRecoknerMergeDownload')->middleware(['verifyMembership']);
//Sourav 3 ends

	Route::get('/premium-calculator/review_of_existing_investment', 'Frontend\Calculators\ReviewOfExistingInvestmentController@index')->name('frontend.review_of_existing_investment')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/review_of_existing_investment-back', 'Frontend\Calculators\ReviewOfExistingInvestmentController@index')->name('frontend.review_of_existing_investment_back')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/review_of_existing_investment_data', 'Frontend\Calculators\ReviewOfExistingInvestmentController@review_of_existing_investment_data')->name('frontend.review_of_existing_investment_data')->middleware(['verifyMembership']);
	Route::post('/premium-calculator/review_of_existing_investment_output', 'Frontend\Calculators\ReviewOfExistingInvestmentController@output')->name('frontend.review_of_existing_investment_output')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/review_of_existing_investment_output_save', 'Frontend\Calculators\ReviewOfExistingInvestmentController@save')->name('frontend.review_of_existing_investment_output_save')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/review_of_existing_investment_output_pdf', 'Frontend\Calculators\ReviewOfExistingInvestmentController@pdf')->name('frontend.review_of_existing_investment_output_pdf')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/review_of_existing_investment-edit', 'Frontend\Calculators\ReviewOfExistingInvestmentController@edit')->name('frontend.review_of_existing_investment_edit')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/review_of_existing_investment-view', 'Frontend\Calculators\ReviewOfExistingInvestmentController@view')->name('frontend.review_of_existing_investment_view')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/review_of_existing_investment-merge-download', 'Frontend\Calculators\ReviewOfExistingInvestmentController@merge_download')->name('frontend.lreview_of_existing_investment_MergeDownload')->middleware(['verifyMembership']);
    
	Route::get('/premium-calculator/goal_calculator', 'Frontend\Calculators\GoalCalculatorController@index')->name('frontend.goal_calculator')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/goal_calculator-back', 'Frontend\Calculators\GoalCalculatorController@index')->name('frontend.goal_calculator_back')->middleware(['verifyMembership']);
	Route::post('/premium-calculator/goal_calculator_output', 'Frontend\Calculators\GoalCalculatorController@output')->name('frontend.goal_calculator_output')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/goal_calculator_output', 'Frontend\Calculators\GoalCalculatorController@output')->name('frontend.goal_calculator_output')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/goal_calculator_output_save', 'Frontend\Calculators\GoalCalculatorController@save')->name('frontend.goal_calculator_output_save')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/goal_calculator_output_pdf', 'Frontend\Calculators\GoalCalculatorController@pdf')->name('frontend.goal_calculator_output_pdf')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/goal_calculator-edit', 'Frontend\Calculators\GoalCalculatorController@edit')->name('frontend.goal_calculatort_edit')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/goal_calculator-view', 'Frontend\Calculators\GoalCalculatorController@view')->name('frontend.goal_calculator_view')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/goal_calculator-merge-download', 'Frontend\Calculators\GoalCalculatorController@merge_download')->name('frontend.goal_calculator_MergeDownload')->middleware(['verifyMembership']);

    Route::get('/premium-calculator/investment_proposal', 'Frontend\Calculators\InvestmentProposalController@index')->name('frontend.investment_proposal')->middleware(['verifyMembership']);
    Route::get('/premium-calculator/investment_proposal-back', 'Frontend\Calculators\InvestmentProposalController@index')->name('frontend.investment_proposal_back')->middleware(['verifyMembership']);
    Route::get('/premium-calculator/investment_proposal_old', 'Frontend\Calculators\InvestmentProposalController@index_old')->name('frontend.investment_proposal_old')->middleware(['verifyMembership']);
    Route::post('/premium-calculator/investment_proposal_output', 'Frontend\Calculators\InvestmentProposalController@output')->name('frontend.investment_proposal_output')->middleware(['verifyMembership']);
    Route::get('/premium-calculator/investment_proposal_output_save', 'Frontend\Calculators\InvestmentProposalController@save')->name('frontend.investment_proposal_output_save')->middleware(['verifyMembership']);
    Route::get('/premium-calculator/investment_proposal_output_pdf', 'Frontend\Calculators\InvestmentProposalController@pdf')->name('frontend.investment_proposal_output_pdf')->middleware(['verifyMembership']);
    Route::get('/premium-calculator/investment_proposal-edit', 'Frontend\Calculators\InvestmentProposalController@edit')->name('frontend.investment_proposal_edit')->middleware(['verifyMembership']);
    Route::get('/premium-calculator/investment_proposal-view', 'Frontend\Calculators\InvestmentProposalController@view')->name('frontend.investment_proposal_view')->middleware(['verifyMembership']);
    Route::get('/premium-calculator/investment_proposal--merge-download', 'Frontend\Calculators\InvestmentProposalController@merge_download')->name('frontend.investment_proposal_download')->middleware(['verifyMembership']);
    
    Route::get('/account/membership-referral', 'Frontend\MembershipReferralController@index')->name('account.membershipReferral')->middleware(['verifyMembership']);
    Route::get('/account/membership-referral-add', 'Frontend\MembershipReferralController@add')->name('account.membershipReferralAdd')->middleware(['verifyMembership']);
    Route::get('/account/membership-referral-delete/{id}', 'Frontend\MembershipReferralController@delete')->name('account.membershipReferralDelete')->middleware(['verifyMembership']);
    Route::get('/account/membership-referral-edit/{id}', 'Frontend\MembershipReferralController@edit')->name('account.membershipReferralEdit')->middleware(['verifyMembership']);
    Route::post('/account/membership-referral-update', 'Frontend\MembershipReferralController@update')->name('account.membershipReferralUpdate')->middleware(['verifyMembership']);
    Route::post('/account/membership-referral-save', 'Frontend\MembershipReferralController@save')->name('account.membershipReferralSave')->middleware(['verifyMembership']);

	Route::get('/swp_comprehensive', 'Frontend\Calculators\PCSwpComprehensiveController@index')->name('frontend.swp_comprehension')->middleware(['verifyMembership']);
	Route::get('/swp_comprehensive-back', 'Frontend\Calculators\PCSwpComprehensiveController@index')->name('frontend.swp_comprehension_back')->middleware(['verifyMembership']);
	Route::post('/premium-calculator/swp_comprehensive_output', 'Frontend\Calculators\PCSwpComprehensiveController@output')->name('frontend.swp_comprehension_output')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/swp_comprehensive_output_pdf', 'Frontend\Calculators\PCSwpComprehensiveController@pdf')->name('frontend.swp_comprehension_output_pdf')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/swp_comprehensive_output_save', 'Frontend\Calculators\PCSwpComprehensiveController@save')->name('frontend.swp_comprehension_output_save')->middleware(['verifyMembership']);
	Route::get('/swp_comprehensive-edit', 'Frontend\Calculators\PCSwpComprehensiveController@edit')->name('frontend.swp_comprehension_edit')->middleware(['verifyMembership']);
	Route::get('/swp_comprehensive-view', 'Frontend\Calculators\PCSwpComprehensiveController@view')->name('frontend.swp_comprehension_view')->middleware(['verifyMembership']);
	Route::get('/swp_comprehensive-merge-download', 'Frontend\Calculators\PCSwpComprehensiveController@merge_download')->name('frontend.swp_comprehension_merge_download')->middleware(['verifyMembership']);
	Route::post('/premium-calculator/swp_check', 'Frontend\Calculators\PCSwpComprehensiveController@swp_check')->name('frontend.swp_check')->middleware(['verifyMembership']);

	Route::get('/premium-calculator/stp_comprehensive', 'Frontend\Calculators\PCStpComprehensiveController@stp_custom_transfer')->name('frontend.stp_custom_transfer')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/stp_comprehensive-back', 'Frontend\Calculators\PCStpComprehensiveController@stp_custom_transfer')->name('frontend.stp_custom_transfer_back')->middleware(['verifyMembership']);
	Route::post('/premium-calculator/stp_comprehensive_output', 'Frontend\Calculators\PCStpComprehensiveController@stp_custom_transfer_output')->name('frontend.stp_custom_transfer_output')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/stp_comprehensive_output_pdf', 'Frontend\Calculators\PCStpComprehensiveController@stp_custom_transfer_output_pdf')->name('frontend.stp_custom_transfer_output_pdf')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/stp_comprehensive_output_save', 'Frontend\Calculators\PCStpComprehensiveController@stp_custom_transfer_output_save')->name('frontend.stp_custom_transfer_output_save')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/stp_comprehensive-edit', 'Frontend\Calculators\PCStpComprehensiveController@edit')->name('frontend.stp_custom_transfer_edit')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/stp_comprehensive-view', 'Frontend\Calculators\PCStpComprehensiveController@view')->name('frontend.stp_custom_transfer_view')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/stp_comprehensive-merge-download', 'Frontend\Calculators\PCStpComprehensiveController@merge_download')->name('frontend.stp_custom_transfer_download')->middleware(['verifyMembership']);

	Route::get('/premium-calculator/swp_periodic_withdrawal', 'Frontend\Calculators\PCSwpPeriodicWithdrawalController@index')->name('frontend.swp_periodic_withdrawal')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/swp_periodic_withdrawal-back', 'Frontend\Calculators\PCSwpPeriodicWithdrawalController@index')->name('frontend.swp_periodic_withdrawal_back')->middleware(['verifyMembership']);
	Route::post('/premium-calculator/swp_periodic_withdrawal_output', 'Frontend\Calculators\PCSwpPeriodicWithdrawalController@output')->name('frontend.swp_periodic_withdrawal_output')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/swp_periodic_withdrawal_output_pdf', 'Frontend\Calculators\PCSwpPeriodicWithdrawalController@pdf')->name('frontend.swp_periodic_withdrawal_output_pdf')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/swp_periodic_withdrawal_output_save', 'Frontend\Calculators\PCSwpPeriodicWithdrawalController@save')->name('frontend.swp_periodic_withdrawal_output_save')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/swp_periodic_withdrawal-edit', 'Frontend\Calculators\PCSwpPeriodicWithdrawalController@edit')->name('frontend.swp_periodic_withdrawal_edit')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/swp_periodic_withdrawal-view', 'Frontend\Calculators\PCSwpPeriodicWithdrawalController@view')->name('frontend.swp_periodic_withdrawal_view')->middleware(['verifyMembership']);
	Route::get('/premium-calculator/swp_periodic_withdrawal-merge-download', 'Frontend\Calculators\PCSwpPeriodicWithdrawalController@merge_download')->name('frontend.swp_periodic_withdrawal_merge_download')->middleware(['verifyMembership']);
	Route::post('/premium-calculator/periodicCheck', 'Frontend\Calculators\PCSwpPeriodicWithdrawalController@periodicCheck')->name('frontend.periodicCheck')->middleware(['verifyMembership']);


  Route::get('/update-mf-scanner-mm-night/{section}', 'AccordLinkController@accord_night');
  Route::get('/update-mf-scanner-mm-night-manual/{section}', 'AccordLinkController@accord_manual_night');


  Route::get('/accord-manual/{section}/{rundate}/{rundateone}/{daypart}', 'AccordLinkController@accord_manual');

