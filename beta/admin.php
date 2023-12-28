<?php
/**
 * Created by PhpStorm.
 * User: Partha
 * Date: 12-12-2019
 * Time: 03:36 PM
 */
 
 
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    
    return 'web cache cleared';
});
     
Route::get('/', 'Admin\AuthController@index')->name('webadmin.index');
Route::post('/login', 'Admin\AuthController@login')->name('webadmin.login');
Route::get('/forgotpassword', 'Admin\AuthController@forgotpassword')->name('webadmin.forgotpassword');
Route::get('/set-admin-user-password/{id}', 'Admin\AuthController@set_password')->name('webadmin.set_password');
Route::post('/update_set_password', 'Admin\AuthController@update_set_password')->name('webadmin.update_set_password');
Route::get('/forgotpassword', 'Admin\AuthController@forgotpassword')->name('webadmin.forget_password');
Route::post('/updateforgotpassword', 'Admin\AuthController@updateforgotpassword')->name('webadmin.updateforgotpassword');
Route::get('access-denied', 'Admin\AccessDenied@index')->name('webadmin.access-denied');

Route::group(['middleware' => ['adminAuth']], function(){
    Route::get('/dashboard', 'Admin\DashboardController@index')->name('webadmin.dashboard');
    Route::get('/change-password', 'Admin\AuthController@changePassword')->name('webadmin.changePassword');
    Route::post('/update-password', 'Admin\AuthController@updatePassword')->name('webadmin.updatePassword');
    Route::get('/logout', 'Admin\AuthController@logout')->name('webadmin.logout');
    Route::get('/settings', 'Admin\AuthController@settings')->name('webadmin.settings');
    Route::post('/update-settings', 'Admin\AuthController@update_settings')->name('webadmin.settingsUpdate');
    
    Route::get('/exam/onlineexam-get-questions', 'Admin\Exam\OnlineExamController@getQuestionList')->name('webadmin.getQuestions');
    Route::get('/exam/onlineexam-save-exam-question', 'Admin\Exam\OnlineExamController@saveExamQuestion')->name('webadmin.saveExamQuestion');
    Route::get('/exam/onlineexam-get-exam-questions', 'Admin\Exam\OnlineExamController@getExamQuestionList')->name('webadmin.getExamQuestionList');
    Route::get('/exam/onlineexam-get-exam-questions-summary', 'Admin\Exam\OnlineExamController@getExamQuestionSummaryList')->name('webadmin.getExamQuestionSummaryList');
    Route::get('/exam/onlineexam-remove-exam-question', 'Admin\Exam\OnlineExamController@removeExamQuestion')->name('webadmin.removeExamQuestion');
});


Route::group(['middleware' => ['adminAuth', 'verifyPermission']], function(){

    /*
    Calc footer
    */

    Route::get('/calculator-footer/add', 'Admin\CalculatorFooterController@add')->name('webadmin.calculatorFooterAdd');
    Route::get('/calculator-footer/index', 'Admin\CalculatorFooterController@index')->name('webadmin.calculatorFooterIndex');
    
    Route::get('swpcomprehensive/add', function(){
        return view('admin.calculator_footers.swpcomprehensiveedit');
    });
    
    Route::post('swpcomprehensive/added','Admin\CalculatorFooterController@updateFooter')->name('webadmin.updateFooter');
    
    Route::get('/swpcomprehensive/index', 'Admin\CalculatorFooterController@ShowSwpCompFooters')->name('webadmin.swpcompfooter');
    
    Route::post('/calculator-footer/save', 'Admin\CalculatorFooterController@save')->name('webadmin.calculatorFooterSave');
    
    Route::get('/mso-model-portfolio/index', 'Admin\CalculatorFooterController@msoIndex')->name('webadmin.msomodelportfolio');
    Route::get('/mso-model-portfolio/input', 'Admin\CalculatorFooterController@msoInput')->name('webadmin.msomodelportfolioinput');
    Route::get('/mso-model-portfolio/output', 'Admin\CalculatorFooterController@msoOutput')->name('webadmin.msomodelportfoliooutput');
    Route::get('/mso-model-portfolio/input2', 'Admin\CalculatorFooterController@msoInput2')->name('webadmin.msomodelportfolioinput');
    
    Route::post('/mso-model-portfolio/readcsv','Admin\CalculatorFooterController@ReadCsv')->name('webadmin.msomodelreadcsv');
    Route::post('/mso-model-portfolio/editcsv','Admin\CalculatorFooterController@EditField')->name('webadmin.EditField');
    Route::get('/mso-model-portfolio/deletecsv','Admin\CalculatorFooterController@DeleteField')->name('webadmin.DeleteField');
    Route::post('/mso-model-portfolio/addcsv','Admin\CalculatorFooterController@AddField')->name('webadmin.AddField');
    Route::get('/mso-model-portfolio/editcsvdata','Admin\CalculatorFooterController@AddCsvPage')->name('webadmin.addcsvpage');
    Route::get('/mso-model-portfolio/addcsvdata','Admin\CalculatorFooterController@AddCsvData')->name('webadmin.editcsvpage');
    Route::get('/mso-model-portfolio/addmsodata','Admin\CalculatorFooterController@AddMsoData')->name('webadmin.addmso');
    Route::get('/mso-model-portfolio/deletemsodata','Admin\CalculatorFooterController@DeleteMsoData')->name('webadmin.deletemso');
    Route::get('/mso-model-portfolio/editmsodata','Admin\CalculatorFooterController@EditMsoData')->name('webadmin.editmso');
    Route::post('/mso-model-portfolio/addmsotype','Admin\CalculatorFooterController@AddMsoType')->name('webadmin.addmsotype');
    Route::get('/mso-model-portfolio/msodataindex',function()
    {
        return view('admin.calculator_footers.msodataindex');
    })->name('webadmin.msodata');
    Route::get('/minimizeindex',function(){
        return view('admin.calculator_footers.justblank');
    })->name('webadmin.justblank');
    //footerworkend

    
    

    
    Route::get('/stat', 'Admin\DashboardController@stat')->name('webadmin.stat');

    Route::get('/home/banner-index', 'Admin\HomeController@bannerIndex')->name('webadmin.bannerIndex');
    Route::get('/home/banner-add', 'Admin\HomeController@bannerAdd')->name('webadmin.bannerAdd');
    Route::post('/home/banner-save', 'Admin\HomeController@bannerSave')->name('webadmin.bannerSave');
    Route::get('/home/banner-edit/{id}', 'Admin\HomeController@bannerEdit')->name('webadmin.bannerEdit');
    Route::post('/home/banner-update/{id}', 'Admin\HomeController@bannerUpdate')->name('webadmin.bannerUpdate');
    Route::get('/home/banner-delete/{id}', 'Admin\HomeController@bannerDelete')->name('webadmin.bannerDelete');
    //for New Banner
    Route::get('/home/banner', 'Admin\BannerController@banner')->name('webadmin.banner');
    Route::post('/home/update-banner/{id}', 'Admin\BannerController@bannerUpdate')->name('webadmin.updateBanner');

    Route::get('/home/whatsnew-index', 'Admin\HomeController@whatsnewIndex')->name('webadmin.whatsnewIndex');
    Route::get('/home/whatsnew-add', 'Admin\HomeController@whatsnewAdd')->name('webadmin.whatsnewAdd');
    Route::post('/home/whatsnew-save', 'Admin\HomeController@whatsnewSave')->name('webadmin.whatsnewSave');
    Route::get('/home/whatsnew-edit/{id}', 'Admin\HomeController@whatsnewEdit')->name('webadmin.whatsnewEdit');
    Route::post('/home/whatsnew-update/{id}', 'Admin\HomeController@whatsnewUpdate')->name('webadmin.whatsnewUpdate');
    Route::get('/home/whatsnew-delete/{id}', 'Admin\HomeController@whatsnewDelete')->name('webadmin.whatsnewDelete');

    Route::get('/home/usefullink-index', 'Admin\HomeController@usefullinkIndex')->name('webadmin.usefullinkIndex');
    Route::get('/home/usefullink-add', 'Admin\HomeController@usefullinkAdd')->name('webadmin.usefullinkAdd');
    Route::post('/home/usefullink-save', 'Admin\HomeController@usefullinkSave')->name('webadmin.usefullinkSave');
    Route::get('/home/usefullink-edit/{id}', 'Admin\HomeController@usefullinkEdit')->name('webadmin.usefullinkEdit');
    Route::post('/home/usefullink-update/{id}', 'Admin\HomeController@usefullinkUpdate')->name('webadmin.usefullinkUpdate');
    Route::get('/home/usefullink-delete/{id}', 'Admin\HomeController@usefullinkDelete')->name('webadmin.usefullinkDelete');

    Route::get('/thought/thought-index', 'Admin\ThoughtController@index')->name('webadmin.thoughtIndex');
    Route::get('/thought/thought-add', 'Admin\ThoughtController@add')->name('webadmin.thoughtAdd');
    Route::post('/thought/thought-save', 'Admin\ThoughtController@save')->name('webadmin.thoughtSave');
    Route::get('/thought/thought-edit/{id}', 'Admin\ThoughtController@edit')->name('webadmin.thoughtEdit');
    Route::post('/thought/thought-update/{id}', 'Admin\ThoughtController@update')->name('webadmin.thoughtUpdate');
    Route::get('/thought/thought-delete/{id}', 'Admin\ThoughtController@delete')->name('webadmin.thoughtDelete');

    Route::get('thought/reorder','Admin\ThoughtController@showDatatable')->name('webadmin.thought.reorder');
    Route::post('thought/reorder','Admin\ThoughtController@updateOrder');

    Route::get('/thought/category-index', 'Admin\ThoughtController@category_index')->name('webadmin.thoughtcategoryIndex');
    Route::get('/thought/category-add', 'Admin\ThoughtController@category_add')->name('webadmin.thoughtcategoryAdd');
    Route::post('/thought/category-save', 'Admin\ThoughtController@category_save')->name('webadmin.thoughtcategorySave');
    Route::get('/thought/category-edit/{id}', 'Admin\ThoughtController@category_edit')->name('webadmin.thoughtcategoryEdit');
    Route::post('/thought/category-update/{id}', 'Admin\ThoughtController@category_update')->name('webadmin.thoughtcategoryUpdate');
    Route::get('/thought/category-delete/{id}', 'Admin\ThoughtController@category_delete')->name('webadmin.thoughtcategoryDelete');


    Route::get('/video-index', 'Admin\VideoController@index')->name('webadmin.videoIndex');
    Route::get('/video-add', 'Admin\VideoController@add')->name('webadmin.videoAdd');
    Route::post('/video-save', 'Admin\VideoController@save')->name('webadmin.videoSave');
    Route::get('/video-edit/{id}', 'Admin\VideoController@edit')->name('webadmin.videoEdit');
    Route::post('/video-update/{id}', 'Admin\VideoController@update')->name('webadmin.videoUpdate');
    Route::get('/video-delete/{id}', 'Admin\VideoController@delete')->name('webadmin.videoDelete');

    Route::get('video/reorder','Admin\VideoController@showDatatable')->name('webadmin.video.reorder');
    Route::post('video/reorder','Admin\VideoController@updateOrder');
    
    Route::get('/how-to-use-video-index', 'Admin\HowtousevideoController@index')->name('webadmin.howtousevideoIndex');
    Route::get('/how-to-use-video-add', 'Admin\HowtousevideoController@add')->name('webadmin.howtousevideoAdd');
    Route::post('/how-to-use-video-save', 'Admin\HowtousevideoController@save')->name('webadmin.howtousevideoSave');
    Route::get('/how-to-use-video-edit/{id}', 'Admin\HowtousevideoController@edit')->name('webadmin.howtousevideoEdit');
    Route::post('/how-to-use-video-update/{id}', 'Admin\HowtousevideoController@update')->name('webadmin.howtousevideoUpdate');
    Route::get('/how-to-use-video-delete/{id}', 'Admin\HowtousevideoController@delete')->name('webadmin.howtousevideoDelete');

    Route::get('how-to-use-video/reorder','Admin\HowtousevideoController@showDatatable')->name('webadmin.howtousevideo.reorder');
    Route::post('how-to-use-video/reorder','Admin\HowtousevideoController@updateOrder');

    Route::get('/blog-index', 'Admin\BlogController@index')->name('webadmin.blogIndex');
    Route::get('/blog-add', 'Admin\BlogController@add')->name('webadmin.blogAdd');
    Route::post('/blog-save', 'Admin\BlogController@save')->name('webadmin.blogSave');
    Route::get('/blog-edit/{id}', 'Admin\BlogController@edit')->name('webadmin.blogEdit');
    Route::post('/blog-update/{id}', 'Admin\BlogController@update')->name('webadmin.blogUpdate');
    Route::get('/blog-delete/{id}', 'Admin\BlogController@delete')->name('webadmin.blogDelete');

    Route::get('blog/reorder','Admin\BlogController@showDatatable')->name('webadmin.blog.reorder');
    Route::post('blog/reorder','Admin\BlogController@updateOrder');

    Route::get('/blogcategory-index', 'Admin\BlogController@category_index')->name('webadmin.blogcategoryIndex');
    Route::get('/blogcategory-add', 'Admin\BlogController@category_add')->name('webadmin.blogcategoryAdd');
    Route::post('/blogcategory-save', 'Admin\BlogController@category_save')->name('webadmin.blogcategorySave');
    Route::get('/blogcategory-edit/{id}', 'Admin\BlogController@category_edit')->name('webadmin.blogcategoryEdit');
    Route::post('/blogcategory-update/{id}', 'Admin\BlogController@category_update')->name('webadmin.blogcategoryUpdate');
    Route::get('/blogcategory-delete/{id}', 'Admin\BlogController@category_delete')->name('webadmin.blogcategoryDelete');

    // Blog Comments

    Route::get('/blog-comments', 'Admin\BlogController@blog_comments')->name('webadmin.blogComments');
    Route::get('/blog-comment-delete/{id}', 'Admin\BlogController@commentDelete')->name('webadmin.commentDelete');
    
    Route::get('/article-comments', 'Admin\ArticleController@article_comments')->name('webadmin.articleComments');
    Route::get('/article-comment-delete/{id}', 'Admin\ArticleController@commentDelete')->name('webadmin.articlecommentDelete');
    
    Route::get('/article-index', 'Admin\ArticleController@index')->name('webadmin.articleIndex');
    Route::get('/article-add', 'Admin\ArticleController@add')->name('webadmin.articleAdd');
    Route::post('/article-save', 'Admin\ArticleController@save')->name('webadmin.articleSave');
    Route::get('/article-edit/{id}', 'Admin\ArticleController@edit')->name('webadmin.articleEdit');
    Route::post('/article-update/{id}', 'Admin\ArticleController@update')->name('webadmin.articleUpdate');
    Route::get('/article-delete/{id}', 'Admin\ArticleController@delete')->name('webadmin.articleDelete');

    Route::get('article/reorder','Admin\ArticleController@showDatatable')->name('webadmin.article.reorder');
    Route::post('article/reorder','Admin\ArticleController@updateOrder');

    //News
    Route::get('/news-index', 'Admin\NewsController@index')->name('webadmin.newsIndex');
    Route::get('/news-add', 'Admin\NewsController@add')->name('webadmin.newsAdd');
    Route::post('/news-save', 'Admin\NewsController@save')->name('webadmin.newsSave');
    Route::get('/news-edit/{id}', 'Admin\NewsController@edit')->name('webadmin.newsEdit');
    Route::post('/news-update/{id}', 'Admin\NewsController@update')->name('webadmin.newsUpdate');
    Route::get('/news-delete/{id}', 'Admin\NewsController@delete')->name('webadmin.newsDelete');

    Route::get('news/reorder','Admin\NewsController@showDatatable')->name('webadmin.news.reorder');
    Route::post('news/reorder','Admin\NewsController@updateOrder');

    //IFA Tools

    //Download Tools

    Route::get('/downloadtools', 'Admin\DownloadtoolController@index')->name('webadmin.downloadtools');
    Route::get('/downloadtool-add', 'Admin\DownloadtoolController@add')->name('webadmin.downloadtoolAdd');
    Route::post('/downloadtool-save', 'Admin\DownloadtoolController@save')->name('webadmin.downloadtoolSave');
    Route::get('/downloadtool-edit/{id}', 'Admin\DownloadtoolController@edit')->name('webadmin.downloadtoolEdit');
    Route::post('/downloadtool-update/{id}', 'Admin\DownloadtoolController@update')->name('webadmin.downloadtoolUpdate');
    Route::get('/downloadtool-delete/{id}', 'Admin\DownloadtoolController@delete')->name('webadmin.downloadtoolDelete');
    
    Route::get('/downloadtool-delete-image/{id}', 'Admin\DownloadtoolController@delete_image')->name('webadmin.downloadtoolDeleteImage');

    Route::get('downloadtools/reorder','Admin\DownloadtoolController@showDatatable')->name('webadmin.downloadtools.reorder');
    Route::post('downloadtools/reorder','Admin\DownloadtoolController@updateOrder');

    //Client Objection Handling

    Route::get('/client-objection-handling', 'Admin\ClientobjectionhandelingController@index')->name('webadmin.coh');
    Route::get('/client-objection-handling-add', 'Admin\ClientobjectionhandelingController@add')->name('webadmin.cohAdd');
    Route::post('/client-objection-handling-save', 'Admin\ClientobjectionhandelingController@save')->name('webadmin.cohSave');
    Route::get('/client-objection-handling-edit/{id}', 'Admin\ClientobjectionhandelingController@edit')->name('webadmin.cohEdit');
    Route::post('/client-objection-handling-update/{id}', 'Admin\ClientobjectionhandelingController@update')->name('webadmin.cohUpdate');
    Route::get('/client-objection-handling-delete/{id}', 'Admin\ClientobjectionhandelingController@delete')->name('webadmin.cohDelete');

    Route::get('client-objection-handling/reorder','Admin\ClientobjectionhandelingController@showDatatable')->name('webadmin.coh.reorder');
    Route::post('client-objection-handling/reorder','Admin\ClientobjectionhandelingController@updateOrder');

    //Book Recommendations

    Route::get('/bookrecommendations', 'Admin\BookrecommendationController@index')->name('webadmin.bookrecommendations');
    Route::get('/bookrecommendation-add', 'Admin\BookrecommendationController@add')->name('webadmin.bookrecommendationAdd');
    Route::post('/bookrecommendation-save', 'Admin\BookrecommendationController@save')->name('webadmin.bookrecommendationSave');
    Route::get('/bookrecommendation-edit/{id}', 'Admin\BookrecommendationController@edit')->name('webadmin.bookrecommendationEdit');
    Route::post('/bookrecommendation-update/{id}', 'Admin\BookrecommendationController@update')->name('webadmin.bookrecommendationUpdate');
    Route::get('/bookrecommendation-delete/{id}', 'Admin\BookrecommendationController@delete')->name('webadmin.bookrecommendationDelete');

    Route::get('bookrecommendations/reorder','Admin\BookrecommendationController@showDatatable')->name('webadmin.bookrecommendations.reorder');
    Route::post('bookrecommendations/reorder','Admin\BookrecommendationController@updateOrder');

    //Product Category

    Route::get('product/product-category', 'Admin\ProductcategoryController@index')->name('webadmin.productcategory');
    Route::get('product/product-category-add', 'Admin\ProductcategoryController@add')->name('webadmin.productcategoryAdd');
    Route::post('product/product-category-save', 'Admin\ProductcategoryController@save')->name('webadmin.productcategorySave');
    Route::get('product/product-category-edit/{id}', 'Admin\ProductcategoryController@edit')->name('webadmin.productcategoryEdit');
    Route::post('product/product-category-update/{id}', 'Admin\ProductcategoryController@update')->name('webadmin.productcategoryUpdate');
    Route::get('product/product-category-delete/{id}', 'Admin\ProductcategoryController@delete')->name('webadmin.productcategoryDelete');

    //Product Suitablity

    Route::get('/product-suitablity', 'Admin\ProductsuitablityController@index')->name('webadmin.productsuitablity');
    Route::get('/product-suitablity-add', 'Admin\ProductsuitablityController@add')->name('webadmin.productsuitablityAdd');
    Route::post('/product-suitablity-save', 'Admin\ProductsuitablityController@save')->name('webadmin.productsuitablitySave');
    Route::get('/product-suitablity-edit/{id}', 'Admin\ProductsuitablityController@edit')->name('webadmin.productsuitablityEdit');
    Route::post('/product-suitablity-update/{id}', 'Admin\ProductsuitablityController@update')->name('webadmin.productsuitablityUpdate');
    Route::get('/product-suitablity-delete/{id}', 'Admin\ProductsuitablityController@delete')->name('webadmin.productsuitablityDelete');

    Route::get('product-suitablity/reorder','Admin\ProductsuitablityController@showDatatable')->name('webadmin.productsuitablity.reorder');
    Route::post('product-suitablity/reorder','Admin\ProductsuitablityController@updateOrder');

    // Premium Banners

    Route::get('premiumbanner/premiumbanner', 'Admin\PremiumbannerController@index')->name('webadmin.premiumbanner');
    Route::get('premiumbanner/premiumbanner-add', 'Admin\PremiumbannerController@add')->name('webadmin.premiumbannerAdd');
    Route::post('premiumbanner/premiumbanner-save', 'Admin\PremiumbannerController@save')->name('webadmin.premiumbannerSave');
    Route::get('premiumbanner/premiumbanner-edit/{id}', 'Admin\PremiumbannerController@edit')->name('webadmin.premiumbannerEdit');
    Route::post('premiumbanner/premiumbanner-update/{id}', 'Admin\PremiumbannerController@update')->name('webadmin.premiumbannerUpdate');
    Route::get('premiumbanner/premiumbanner-delete/{id}', 'Admin\PremiumbannerController@delete')->name('webadmin.premiumbannerDelete');

    Route::get('premiumbanner/reorder','Admin\PremiumbannerController@showDatatable')->name('webadmin.premiumbanner.reorder');
    Route::post('premiumbanner/reorder','Admin\PremiumbannerController@updateOrder');

    Route::get('premiumbanner/premiumbannercategory', 'Admin\PremiumbannerController@category_index')->name('webadmin.premiumbannercategory');
    Route::get('premiumbanner/premiumbannercategory-add', 'Admin\PremiumbannerController@category_add')->name('webadmin.premiumbannercategoryAdd');
    Route::post('premiumbanner/premiumbannercategory-save', 'Admin\PremiumbannerController@category_save')->name('webadmin.premiumbannercategorySave');
    Route::get('premiumbanner/premiumbannercategory-edit/{id}', 'Admin\PremiumbannerController@category_edit')->name('webadmin.premiumbannercategoryEdit');
    Route::post('premiumbanner/premiumbannercategory-update/{id}', 'Admin\PremiumbannerController@category_update')->name('webadmin.premiumbannercategoryUpdate');
    Route::get('premiumbanner/premiumbannercategory-delete/{id}', 'Admin\PremiumbannerController@category_delete')->name('webadmin.premiumbannercategoryDelete');

    // Marketing videos

    Route::get('/marketingvideo', 'Admin\MarketingvideoController@index')->name('webadmin.marketingvideo');
    Route::get('/marketingvideo-add', 'Admin\MarketingvideoController@add')->name('webadmin.marketingvideoAdd');
    Route::post('/marketingvideo-save', 'Admin\MarketingvideoController@save')->name('webadmin.marketingvideoSave');
    Route::get('/marketingvideo-edit/{id}', 'Admin\MarketingvideoController@edit')->name('webadmin.marketingvideoEdit');
    Route::post('/marketingvideo-update/{id}', 'Admin\MarketingvideoController@update')->name('webadmin.marketingvideoUpdate');
    Route::get('/marketingvideo-delete/{id}', 'Admin\MarketingvideoController@delete')->name('webadmin.marketingvideoDelete');

    Route::get('marketingvideo/reorder','Admin\MarketingvideoController@showDatatable')->name('webadmin.marketingvideo.reorder');
    Route::post('marketingvideo/reorder','Admin\MarketingvideoController@updateOrder');

    Route::get('/marketingvideocategory', 'Admin\MarketingvideoController@category_index')->name('webadmin.marketingvideocategory');
    Route::get('/marketingvideocategory-add', 'Admin\MarketingvideoController@category_add')->name('webadmin.marketingvideocategoryAdd');
    Route::post('/marketingvideocategory-save', 'Admin\MarketingvideoController@category_save')->name('webadmin.marketingvideocategorySave');
    Route::get('/marketingvideocategory-edit/{id}', 'Admin\MarketingvideoController@category_edit')->name('webadmin.marketingvideocategoryEdit');
    Route::post('/marketingvideocategory-update/{id}', 'Admin\MarketingvideoController@category_update')->name('webadmin.marketingvideocategoryUpdate');
    Route::get('/marketingvideocategory-delete/{id}', 'Admin\MarketingvideoController@category_delete')->name('webadmin.marketingvideocategoryDelete');


    //Sales Presenters

    //Category
    Route::get('/salespresentercategory', 'Admin\SalespresenterController@category_index')->name('webadmin.salespresentercategory');
    Route::get('/salespresentercategory-add', 'Admin\SalespresenterController@category_add')->name('webadmin.salespresentercategoryAdd');
    Route::post('/salespresentercategory-save', 'Admin\SalespresenterController@category_save')->name('webadmin.salespresentercategorySave');
    Route::get('/salespresentercategory-edit/{id}', 'Admin\SalespresenterController@category_edit')->name('webadmin.salespresentercategoryEdit');
    Route::post('/salespresentercategory-update/{id}', 'Admin\SalespresenterController@category_update')->name('webadmin.salespresentercategoryUpdate');
    Route::get('/salespresentercategory-delete/{id}', 'Admin\SalespresenterController@category_delete')->name('webadmin.salespresentercategoryDelete');

    //Faq

    Route::get('/salespresenterfaq', 'Admin\SalespresenterfaqController@index')->name('webadmin.salespresenterfaq');
    Route::get('/salespresenterfaq-add', 'Admin\SalespresenterfaqController@add')->name('webadmin.salespresenterfaqAdd');
    Route::post('/salespresenterfaq-save', 'Admin\SalespresenterfaqController@save')->name('webadmin.salespresenterfaqSave');
    Route::get('/salespresenterfaq-edit/{id}', 'Admin\SalespresenterfaqController@edit')->name('webadmin.salespresenterfaqEdit');
    Route::post('/salespresenterfaq-update/{id}', 'Admin\SalespresenterfaqController@update')->name('webadmin.salespresenterfaqUpdate');
    Route::get('/salespresenterfaq-delete/{id}', 'Admin\SalespresenterfaqController@delete')->name('webadmin.salespresenterfaqDelete');

    //Sales Presenters post

    Route::get('/salespresentersoftcopy', 'Admin\SalespresentersoftcopyController@index')->name('webadmin.salespresentersoftcopy');
    Route::get('/salespresentersoftcopy-add', 'Admin\SalespresentersoftcopyController@add')->name('webadmin.salespresentersoftcopyAdd');
    Route::post('/salespresentersoftcopy-save', 'Admin\SalespresentersoftcopyController@save')->name('webadmin.salespresentersoftcopySave');
    Route::get('/salespresentersoftcopy-edit/{id}', 'Admin\SalespresentersoftcopyController@edit')->name('webadmin.salespresentersoftcopyEdit');
    Route::post('/salespresentersoftcopy-update/{id}', 'Admin\SalespresentersoftcopyController@update')->name('webadmin.salespresentersoftcopyUpdate');
    Route::get('/salespresentersoftcopy-delete/{id}', 'Admin\SalespresentersoftcopyController@delete')->name('webadmin.salespresentersoftcopyDelete');

    Route::get('salespresentersoftcopy/reorder','Admin\SalespresentersoftcopyController@showDatatable')->name('webadmin.salespresentersoftcopy.reorder');
    Route::post('salespresentersoftcopy/reorder','Admin\SalespresentersoftcopyController@updateOrder');

    //Suggested Sales Presenters post

    Route::get('/suggested-salespresentersoftcopy', 'Admin\SuggestedSalespresenterController@index')->name('webadmin.suggestedSalespresentersoftcopy');
    Route::get('/suggested-salespresentersoftcopy-add', 'Admin\SuggestedSalespresenterController@add')->name('webadmin.suggestedSalespresentersoftcopyAdd');
    Route::get('/suggested-salespresentersoftcopy-checklist/{list_name}', 'Admin\SuggestedSalespresenterController@checkSavelist')->name('webadmin.suggestedSalespresentersoftcopycheck');
    Route::post('/suggested-salespresentersoftcopy-savelist', 'Admin\SuggestedSalespresenterController@saveListData')->name('webadmin.suggestedSalespresentersoftcopySave');
    Route::get('/suggested-salespresentersoftcopy-edit/{id}', 'Admin\SuggestedSalespresenterController@edit')->name('webadmin.suggestedSalespresentersoftcopyEdit');
    Route::post('/suggested-salespresentersoftcopy-update', 'Admin\SuggestedSalespresenterController@updateListData')->name('webadmin.suggestedSalespresentersoftcopyUpdate');
    Route::get('/suggested-salespresentersoftcopy-delete/{id}', 'Admin\SuggestedSalespresenterController@deleteList')->name('webadmin.suggestedSalespresentersoftcopyDelete');
    Route::get('/suggested-salespresentersoftcopy-sort/{id}', 'Admin\SuggestedSalespresenterController@arrangeSaveList')->name('webadmin.suggestedSalespresentersoftcopySort');
    Route::post('/suggested-salespresentersoftcopy-update-position', 'Admin\SuggestedSalespresenterController@updatePosition')->name('webadmin.suggestedSalespresentersoftcopyUpdatePosition');
    //Users

    Route::get('/users/all-user', 'Admin\UserController@index')->name('webadmin.users');

    Route::get('/users/register', 'Admin\UserController@create')->name('webadmin.users.register');
    Route::post('/users/register-save', 'Admin\UserController@store')->name('webadmin.users.store');
    Route::get('/users/user-edit/{id}', 'Admin\UserController@edit')->name('webadmin.userEdit');
    Route::post('/users/user-update/{id}', 'Admin\UserController@update')->name('webadmin.userUpdate');
    Route::get('/users/user-delete/{id}', 'Admin\UserController@delete')->name('webadmin.userDelete');
    
    // Export to excel
    Route::get('/users/exportExcel','Admin\UserController@exportExcel')->name('webadmin.users.exportExcel');
    // Export to csv
    Route::get('/users/exportCSV','Admin\UserController@exportCSV')->name('webadmin.users.exportCSV');

    //User data - Excel/csv - Paid/non paid wise - period wise (for subscription and also expiry). In user database, Pls confirm what is given in column L,M,N
    Route::get('/users/exportusers-csv','Admin\UserController@exportusers_csv')->name('webadmin.users.exportusers-csv');
    Route::post('/users/exportusersCSV/download','Admin\UserController@exportusersCSV')->name('webadmin.users.exportusersCSVdownload');
    
    // Export subscription datas of perticular user
    Route::get('/users/exportuser-subscription-csv','Admin\UserController@exportuser_subscription_csv')->name('webadmin.users.exportuser-subscription-csv');
    Route::post('/users/exportusersubscriptionCSV/download','Admin\UserController@exportusersubscriptionCSV')->name('webadmin.users.exportusersubscriptionCSV');
    
    //Subscription 

    Route::get('/subscriptions/all-subscription/{user_id}', 'Admin\SubscriptionController@index')->name('webadmin.subscriptions');
    

    Route::get('/subscriptions/add/{user_id}', 'Admin\SubscriptionController@create')->name('webadmin.subscriptions.create');
    Route::post('/subscriptions/subscription-save', 'Admin\SubscriptionController@store')->name('webadmin.subscriptions.store');
    Route::get('/subscriptions/subscription-edit/{id}', 'Admin\SubscriptionController@edit')->name('webadmin.subscriptionEdit');
    Route::post('/subscriptions/subscription-update/{id}', 'Admin\SubscriptionController@update')->name('webadmin.subscriptionUpdate');
    Route::get('/subscriptions/subscription-delete/{id}', 'Admin\SubscriptionController@delete')->name('webadmin.subscriptionDelete');
    Route::get('/subscriptions/subscription-email/{id}', 'Admin\SubscriptionController@email')->name('webadmin.subscriptionEmail');
    Route::get('/subscriptions/subscription-download/{id}', 'Admin\SubscriptionController@downloads')->name('webadmin.subscriptionDownload');

    Route::get('/testimonial-index', 'Admin\TestimonialController@index')->name('webadmin.testimonialIndex');
    Route::get('/testimonial-add', 'Admin\TestimonialController@add')->name('webadmin.testimonialAdd');
    Route::post('/testimonial-save', 'Admin\TestimonialController@save')->name('webadmin.testimonialSave');
    Route::get('/testimonial-edit/{id}', 'Admin\TestimonialController@edit')->name('webadmin.testimonialEdit');
    Route::post('/testimonial-update/{id}', 'Admin\TestimonialController@update')->name('webadmin.testimonialUpdate');
    Route::get('/testimonial-delete/{id}', 'Admin\TestimonialController@delete')->name('webadmin.testimonialDelete');

    Route::get('/pages/page-index', 'Admin\PageController@index')->name('webadmin.pageIndex');
    Route::get('/pages/page-add', 'Admin\PageController@add')->name('webadmin.pageAdd');
    Route::post('/pages/page-save', 'Admin\PageController@save')->name('webadmin.pageSave');
    Route::get('/pages/page-edit/{id}', 'Admin\PageController@edit')->name('webadmin.pageEdit');
    Route::post('/pages/page-update/{id}', 'Admin\PageController@update')->name('webadmin.pageUpdate');


    Route::get('/page-share/index', 'Admin\PageshareimageController@index')->name('webadmin.pageShareIndex');
    Route::get('/page-share/add', 'Admin\PageshareimageController@add')->name('webadmin.pageShareAdd');
    Route::post('/page-share/save', 'Admin\PageshareimageController@save')->name('webadmin.pageShareSave');
    Route::get('/page-share/edit/{id}', 'Admin\PageshareimageController@edit')->name('webadmin.pageShareEdit');
    Route::post('/page-share/update/{id}', 'Admin\PageshareimageController@update')->name('webadmin.pageShareUpdate');
    Route::get('/page-share/delete/{id}', 'Admin\PageshareimageController@delete')->name('webadmin.pageShareDelete');


     Route::get('/calculator-heading/index', 'Admin\CalculatorHeadingController@index')->name('webadmin.calculatorHeadingIndex');
    Route::get('/calculator-heading/add', 'Admin\CalculatorHeadingController@add')->name('webadmin.calculatorHeadingAdd');
    Route::post('/calculator-heading/save', 'Admin\CalculatorHeadingController@save')->name('webadmin.calculatorHeadingSave');
    Route::get('/calculator-heading/edit/{id}', 'Admin\CalculatorHeadingController@edit')->name('webadmin.calculatorHeadingEdit');
    Route::post('/calculator-heading/update/{id}', 'Admin\CalculatorHeadingController@update')->name('webadmin.calculatorHeadingUpdate');
    Route::get('/calculator-heading/delete/{id}', 'Admin\CalculatorHeadingController@delete')->name('webadmin.calculatorHeadingDelete');

    //SURVEYS

    //SURVEY CATEGORY
    Route::get('/surveycategory', 'Admin\SurveycategoryController@category_index')->name('webadmin.surveycategory');
    Route::get('/surveycategory-add', 'Admin\SurveycategoryController@category_add')->name('webadmin.surveycategoryAdd');
    Route::post('/surveycategory-save', 'Admin\SurveycategoryController@category_save')->name('webadmin.surveycategorySave');
    Route::get('/surveycategory-edit/{id}', 'Admin\SurveycategoryController@category_edit')->name('webadmin.surveycategoryEdit');
    Route::post('/surveycategory-update/{id}', 'Admin\SurveycategoryController@category_update')->name('webadmin.surveycategoryUpdate');
    Route::get('/surveycategory-delete/{id}', 'Admin\SurveycategoryController@category_delete')->name('webadmin.surveycategoryDelete');

    Route::get('/survey', 'Admin\SurveycategoryController@index')->name('webadmin.survey');
    Route::get('/survey-add', 'Admin\SurveycategoryController@add')->name('webadmin.surveyAdd');
    Route::post('/survey-save', 'Admin\SurveycategoryController@save')->name('webadmin.surveySave');
    Route::get('/survey-edit/{id}', 'Admin\SurveycategoryController@edit')->name('webadmin.surveyEdit');
    Route::post('/survey-update/{id}', 'Admin\SurveycategoryController@update')->name('webadmin.surveyUpdate');
    Route::get('/survey-delete/{id}', 'Admin\SurveycategoryController@delete')->name('webadmin.surveyDelete');

    //Success Story

    Route::get('/successstory', 'Admin\SuccessstoryController@index')->name('webadmin.successstory');
    Route::get('/successstory-add', 'Admin\SuccessstoryController@add')->name('webadmin.successstoryAdd');
    Route::post('/successstory-save', 'Admin\SuccessstoryController@save')->name('webadmin.successstorySave');
    Route::get('/successstory-edit/{id}', 'Admin\SuccessstoryController@edit')->name('webadmin.successstoryEdit');
    Route::post('/successstory-update/{id}', 'Admin\SuccessstoryController@update')->name('webadmin.successstoryUpdate');
    Route::get('/successstory-delete/{id}', 'Admin\SuccessstoryController@delete')->name('webadmin.successstoryDelete');

    Route::get('successstory/reorder','Admin\SuccessstoryController@showDatatable')->name('webadmin.successstory.reorder');
    Route::post('successstory/reorder','Admin\SuccessstoryController@updateOrder');

    //Stationary

    Route::get('/stationary', 'Admin\StationaryController@index')->name('webadmin.stationary');
    Route::get('/stationary-add', 'Admin\StationaryController@add')->name('webadmin.stationaryAdd');
    Route::post('/stationary-save', 'Admin\StationaryController@save')->name('webadmin.stationarySave');
    Route::get('/stationary-edit/{id}', 'Admin\StationaryController@edit')->name('webadmin.stationaryEdit');
    Route::post('/stationary-update/{id}', 'Admin\StationaryController@update')->name('webadmin.stationaryUpdate');
    Route::get('/stationary-delete/{id}', 'Admin\StationaryController@delete')->name('webadmin.stationaryDelete');

    Route::get('/stationary/product-type', 'Admin\StationaryController@product_type_index')->name('webadmin.stationaryProductType');
    Route::get('/stationary/product-type-add', 'Admin\StationaryController@product_type_add')->name('webadmin.stationaryProductTypeAdd');
    Route::post('/stationary/product-type-save', 'Admin\StationaryController@product_type_save')->name('webadmin.stationaryProductTypeSave');
    Route::get('/stationary/product-type-edit/{id}', 'Admin\StationaryController@product_type_edit')->name('webadmin.stationaryProductTypeEdit');
    Route::post('/stationary/product-type-update/{id}', 'Admin\StationaryController@product_type_update')->name('webadmin.stationaryProductTypeUpdate');
    Route::get('/stationary/product-type-delete/{id}', 'Admin\StationaryController@product_type_delete')->name('webadmin.stationaryProductTypeDelete');

    Route::get('/stationary/category', 'Admin\StationaryController@category_index')->name('webadmin.stationaryCategory');
    Route::get('/stationary/category-add', 'Admin\StationaryController@category_add')->name('webadmin.stationaryCategoryAdd');
    Route::post('/stationary/category-save', 'Admin\StationaryController@category_save')->name('webadmin.stationaryCategorySave');
    Route::get('/stationary/category-edit/{id}', 'Admin\StationaryController@category_edit')->name('webadmin.stationaryCategoryEdit');
    Route::post('/stationary/category-update/{id}', 'Admin\StationaryController@category_update')->name('webadmin.stationaryCategoryUpdate');
    Route::get('/stationary/category-delete/{id}', 'Admin\StationaryController@category_delete')->name('webadmin.stationaryCategoryDelete');

    Route::get('/stationary/sub-category', 'Admin\StationaryController@sub_category_index')->name('webadmin.stationarySubCategory');
    Route::get('/stationary/sub-category-add', 'Admin\StationaryController@sub_category_add')->name('webadmin.stationarySubCategoryAdd');
    Route::post('/stationary/sub-category-save', 'Admin\StationaryController@sub_category_save')->name('webadmin.stationarySubCategorySave');
    Route::get('/stationary/sub-category-edit/{id}', 'Admin\StationaryController@sub_category_edit')->name('webadmin.stationarySubCategoryEdit');
    Route::post('/stationary/sub-category-update/{id}', 'Admin\StationaryController@sub_category_update')->name('webadmin.stationarySubCategoryUpdate');
    Route::get('/stationary/sub-category-delete/{id}', 'Admin\StationaryController@sub_category_delete')->name('webadmin.stationarySubCategoryDelete');

    Route::get('/stationary/gift-card', 'Admin\StationaryController@gift_card_index')->name('webadmin.stationaryGiftCard');
    Route::get('/stationary/gift-card-add', 'Admin\StationaryController@gift_card_add')->name('webadmin.stationaryGiftCardAdd');
    Route::post('/stationary/gift-card-save', 'Admin\StationaryController@gift_card_save')->name('webadmin.stationaryGiftCardSave');
    Route::get('/stationary/gift-card-edit/{id}', 'Admin\StationaryController@gift_card_edit')->name('webadmin.stationaryGiftCardEdit');
    Route::post('/stationary/gift-card-update/{id}', 'Admin\StationaryController@gift_card_update')->name('webadmin.stationaryGiftCardUpdate');
    Route::get('/stationary/gift-card-delete/{id}', 'Admin\StationaryController@gift_card_delete')->name('webadmin.stationaryGiftCardDelete');

    Route::get('/stationary/group-create', 'Admin\StationaryController@group_index')->name('webadmin.stationaryGroupCreate');
    Route::post('/stationary/group-save', 'Admin\StationaryController@saveGroup')->name('webadmin.stationarySaveGroup');
    Route::get('/stationary/group-index', 'Admin\StationaryController@groupIndex')->name('webadmin.stationaryGroupIndex');
    Route::get('/stationary/group-user/{id}', 'Admin\StationaryController@groupUserindex')->name('webadmin.stationaryGroupUserindex');
    Route::post('/stationary/group-user-remove', 'Admin\StationaryController@removeGroupUser')->name('webadmin.stationaryRemoveGroupUser');
    Route::get('/stationary/group-remove/{id}', 'Admin\StationaryController@removeGroup')->name('webadmin.stationaryRemoveGroup');
    Route::get('/stationary/get-groups', 'Admin\StationaryController@getGroups')->name('webadmin.getStationaryGroups');
    Route::get('/stationary/get-user-type', 'Admin\StationaryController@getUsertype')->name('webadmin.getStationaryUsertype');
    Route::get('/stationary/get-user', 'Admin\StationaryController@getUser')->name('webadmin.getStationaryUser');
    
    // Manage package
    
    Route::get('/manage-package/{id}', 'Admin\StationaryController@manage_package')->name('webadmin.managepackage');
    Route::get('/manage-premade/{id}', 'Admin\StationaryController@managepremade')->name('webadmin.managepremade');
    Route::post('/manage-package-save', 'Admin\StationaryController@manage_package_save')->name('webadmin.managepackageSave');
    Route::post('/manage-premade-save', 'Admin\StationaryController@manage_premade_save')->name('webadmin.managepremadeSave');
    Route::get('/manage-package-delete/{id}', 'Admin\StationaryController@managepackagedelete')->name('webadmin.managepackageDelete');
    
    //Gallery

    Route::get('/gallery', 'Admin\GalleryController@index')->name('webadmin.gallery');
    Route::get('/gallery-add', 'Admin\GalleryController@add')->name('webadmin.galleryAdd');
    Route::post('/gallery-save', 'Admin\GalleryController@save')->name('webadmin.gallerySave');
    Route::get('/gallery-edit/{id}', 'Admin\GalleryController@edit')->name('webadmin.galleryEdit');
    Route::post('/gallery-update/{id}', 'Admin\GalleryController@update')->name('webadmin.galleryUpdate');
    Route::get('/gallery-delete/{id}', 'Admin\GalleryController@delete')->name('webadmin.galleryDelete');

    //Business Faq

    //Category
    
    Route::get('/businessfaqcategory', 'Admin\BusinessfaqcategoryController@index')->name('webadmin.businessfaqcategory');
    Route::get('/businessfaqcategory-add', 'Admin\BusinessfaqcategoryController@add')->name('webadmin.businessfaqcategoryAdd');
    Route::post('/businessfaqcategory-save', 'Admin\BusinessfaqcategoryController@save')->name('webadmin.businessfaqcategorySave');
    Route::get('/businessfaqcategory-edit/{id}', 'Admin\BusinessfaqcategoryController@edit')->name('webadmin.businessfaqcategoryEdit');
    Route::post('/businessfaqcategory-update/{id}', 'Admin\BusinessfaqcategoryController@update')->name('webadmin.businessfaqcategoryUpdate');
    Route::get('/businessfaqcategory-delete/{id}', 'Admin\BusinessfaqcategoryController@delete')->name('webadmin.businessfaqcategoryDelete');
    Route::get('/businessfaq-by-category/{id}', 'Admin\BusinessfaqController@businessfaq_by_category')->name('webadmin.businessfaq-by-category');
    //FAQ

    Route::get('/businessfaq', 'Admin\BusinessfaqController@index')->name('webadmin.businessfaq');
    Route::get('/businessfaq-add', 'Admin\BusinessfaqController@add')->name('webadmin.businessfaqAdd');
    Route::post('/businessfaq-save', 'Admin\BusinessfaqController@save')->name('webadmin.businessfaqSave');
    Route::get('/businessfaq-edit/{id}', 'Admin\BusinessfaqController@edit')->name('webadmin.businessfaqEdit');
    Route::post('/businessfaq-update/{id}', 'Admin\BusinessfaqController@update')->name('webadmin.businessfaqUpdate');
    Route::get('/businessfaq-delete/{id}', 'Admin\BusinessfaqController@delete')->name('webadmin.businessfaqDelete');

    Route::get('businessfaq/reorder','Admin\BusinessfaqController@showDatatable')->name('webadmin.businessfaq.reorder');
    Route::post('businessfaq/reorder','Admin\BusinessfaqController@updateOrder');
    
    //Product Faq 

    //Category
    
    Route::get('/productfaqcategory', 'Admin\ProductfaqController@category_index')->name('webadmin.productfaqcategory');
    Route::get('/productfaqcategory-add', 'Admin\ProductfaqController@category_add')->name('webadmin.productfaqcategoryAdd');
    Route::post('/productfaqcategory-save', 'Admin\ProductfaqController@category_save')->name('webadmin.productfaqcategorySave');
    Route::get('/productfaqcategory-edit/{id}', 'Admin\ProductfaqController@category_edit')->name('webadmin.productfaqcategoryEdit');
    Route::post('/productfaqcategory-update/{id}', 'Admin\ProductfaqController@category_update')->name('webadmin.productfaqcategoryUpdate');
    Route::get('/productfaqcategory-delete/{id}', 'Admin\ProductfaqController@category_delete')->name('webadmin.productfaqcategoryDelete');
    Route::get('/productfaq-by-category/{id}', 'Admin\ProductfaqController@productfaq_by_category')->name('webadmin.productfaq-by-category');
    
    //FAQ

    Route::get('/productfaq', 'Admin\ProductfaqController@index')->name('webadmin.productfaq');
    Route::get('/productfaq-add', 'Admin\ProductfaqController@add')->name('webadmin.productfaqAdd');
    Route::post('/productfaq-save', 'Admin\ProductfaqController@save')->name('webadmin.productfaqSave');
    Route::get('/productfaq-edit/{id}', 'Admin\ProductfaqController@edit')->name('webadmin.productfaqEdit');
    Route::post('/productfaq-update/{id}', 'Admin\ProductfaqController@update')->name('webadmin.productfaqUpdate');
    Route::get('/productfaq-delete/{id}', 'Admin\ProductfaqController@delete')->name('webadmin.productfaqDelete');

    Route::get('productfaq/reorder','Admin\ProductfaqController@showDatatable')->name('webadmin.productfaq.reorder');
    Route::post('productfaq/reorder','Admin\ProductfaqController@updateOrder');

    //Quick Links

    Route::get('/quicklink', 'Admin\QuicklinkController@index')->name('webadmin.quicklink');
    Route::get('/quicklink-add', 'Admin\QuicklinkController@add')->name('webadmin.quicklinkAdd');
    Route::post('/quicklink-save', 'Admin\QuicklinkController@save')->name('webadmin.quicklinkSave');
    Route::get('/quicklink-edit/{id}', 'Admin\QuicklinkController@edit')->name('webadmin.quicklinkEdit');
    Route::post('/quicklink-update/{id}', 'Admin\QuicklinkController@update')->name('webadmin.quicklinkUpdate');
    Route::get('/quicklink-delete/{id}', 'Admin\QuicklinkController@delete')->name('webadmin.quicklinkDelete');
    
    //Quick Links Banner

    Route::get('/quicklink-banner', 'Admin\QuicklinkbannerController@index')->name('webadmin.quicklinkbanner');
    Route::get('/quicklink-banner-add', 'Admin\QuicklinkbannerController@add')->name('webadmin.quicklinkbannerAdd');
    Route::post('/quicklink-banner-save', 'Admin\QuicklinkbannerController@save')->name('webadmin.quicklinkbannerSave');
    Route::get('/quicklink-banner-edit/{id}', 'Admin\QuicklinkbannerController@edit')->name('webadmin.quicklinkbannerEdit');
    Route::post('/quicklink-banner-update/{id}', 'Admin\QuicklinkbannerController@update')->name('webadmin.quicklinkbannerUpdate');
    Route::get('/quicklink-banner-delete/{id}', 'Admin\QuicklinkbannerController@delete')->name('webadmin.quicklinkbannerDelete');

    //Order Management

    Route::get('order/orders', 'Admin\OrderController@index')->name('webadmin.order.orders');
    Route::get('order/orders-edit/{id}', 'Admin\OrderController@edit')->name('webadmin.ordersEdit');
    Route::get('order/orders-add/{id}', 'Admin\OrderController@add')->name('webadmin.ordersAdd');
    Route::post('order/orders-update/{id}', 'Admin\OrderController@update')->name('webadmin.ordersUpdate');
    Route::get('order/orders-delete/{id}', 'Admin\OrderController@cancel')->name('webadmin.ordersDelete');
    Route::get('order/orders-download/{id}', 'Admin\OrderController@orderDownload')->name('webadmin.orderDownload');
    
    Route::get('order/request-package/{id}', 'Admin\OrderController@request_package')->name('webadmin.request-package');
    
    // Order Export datas of datewise
    Route::get('/order/export-order-data-datewise','Admin\OrderController@export_order_data_datewise')->name('webadmin.orders.export-order-data-datewise');
    Route::post('/order/exportorderdatadatewise/download','Admin\OrderController@exportorderdatadatewise')->name('webadmin.orders.exportorderdatadatewise');

    //User Purchase History all details (Notebook, Webinar etc)
    
    Route::get('/order/export-order-data-by-user','Admin\OrderController@export_order_data_by_user')->name('webadmin.orders.export-order-data-by-user');
    Route::post('/order/exportorderdatabyuser/download','Admin\OrderController@exportorderdatabyuser')->name('webadmin.orders.exportorderdatabyuser');
    
    //Purchase History by Products (Notebook, Webinar etc)
    
    Route::get('/order/export-order-data-by-product','Admin\OrderController@export_order_data_by_product')->name('webadmin.orders.export-order-data-by-product');
    Route::post('/order/exportorderdatabyproduct/download','Admin\OrderController@exportorderdatabyproduct')->name('webadmin.orders.exportorderdatabyproduct');
    
    //Coupon Management System

    Route::get('coupon/coupons', 'Admin\CouponController@index')->name('webadmin.coupons');
    Route::get('coupon/coupons-add', 'Admin\CouponController@add')->name('webadmin.couponsAdd');
    Route::post('coupon/coupons-save', 'Admin\CouponController@save')->name('webadmin.couponsSave');
    Route::get('coupon/coupons-edit/{id}', 'Admin\CouponController@edit')->name('webadmin.couponsEdit');
    Route::post('coupon/coupons-update/{id}', 'Admin\CouponController@update')->name('webadmin.couponsUpdate');
    Route::get('coupon/coupons-delete/{id}', 'Admin\CouponController@delete')->name('webadmin.couponsDelete');

    //Coupon Management System

    Route::get('package/coupons', 'Admin\PackageCouponController@index')->name('webadmin.package');
    Route::get('package/coupons-add', 'Admin\PackageCouponController@add')->name('webadmin.packageAdd');
    Route::post('package/coupons-save', 'Admin\PackageCouponController@save')->name('webadmin.packageSave');
    Route::get('package/coupons-edit/{id}', 'Admin\PackageCouponController@edit')->name('webadmin.packageEdit');
    Route::post('package/coupons-update/{id}', 'Admin\PackageCouponController@update')->name('webadmin.packageUpdate');
    Route::get('package/coupons-delete/{id}', 'Admin\PackageCouponController@delete')->name('webadmin.packageDelete');

    //Sample Report

    Route::get('/samplereport', 'Admin\SamplereportController@index')->name('webadmin.samplereport');
    Route::get('/samplereport-add', 'Admin\SamplereportController@add')->name('webadmin.samplereportAdd');
    Route::post('/samplereport-save', 'Admin\SamplereportController@save')->name('webadmin.samplereportSave');
    Route::get('/samplereport-edit/{id}', 'Admin\SamplereportController@edit')->name('webadmin.samplereportEdit');
    Route::post('/samplereport-update/{id}', 'Admin\SamplereportController@update')->name('webadmin.samplereportUpdate');
    Route::get('/samplereport-delete/{id}', 'Admin\SamplereportController@delete')->name('webadmin.samplereportDelete');

    //Sample pdf

    Route::get('samplepdf/samplepdf', 'Admin\SamplepdfController@index')->name('webadmin.samplepdf');
    Route::get('samplepdf/samplepdf-add', 'Admin\SamplepdfController@add')->name('webadmin.samplepdfAdd');
    Route::post('samplepdf/samplepdf-save', 'Admin\SamplepdfController@save')->name('webadmin.samplepdfSave');
    Route::get('samplepdf/samplepdf-edit/{id}', 'Admin\SamplepdfController@edit')->name('webadmin.samplepdfEdit');
    Route::post('samplepdf/samplepdf-update/{id}', 'Admin\SamplepdfController@update')->name('webadmin.samplepdfUpdate');
    Route::get('samplepdf/samplepdf-delete/{id}', 'Admin\SamplepdfController@delete')->name('webadmin.samplepdfDelete');
    
    //Sales Presenter Pdf

    Route::get('sales-presenter-pdf/sales-presenter-pdf', 'Admin\SalesPresenterPdfController@index')->name('webadmin.salesPresenterPdf');
    Route::get('sales-presenter-pdf/sales-presenter-pdf-add', 'Admin\SalesPresenterPdfController@add')->name('webadmin.salesPresenterPdfAdd');
    Route::post('sales-presenter-pdf/sales-presenter-pdf-save', 'Admin\SalesPresenterPdfController@save')->name('webadmin.salesPresenterPdfSave');
    Route::get('sales-presenter-pdf/sales-presenter-pdf-edit/{id}', 'Admin\SalesPresenterPdfController@edit')->name('webadmin.salesPresenterPdfEdit');
    Route::post('sales-presenter-pdf/sales-presenter-pdf-update/{id}', 'Admin\SalesPresenterPdfController@update')->name('webadmin.salesPresenterPdfUpdate');
    Route::get('sales-presenter-pdf/sales-presenter-pdf-delete/{id}', 'Admin\SalesPresenterPdfController@delete')->name('webadmin.salesPresenterPdfDelete');
    
    Route::get('sales-presenter-pdf/reorder','Admin\SalesPresenterPdfController@showDatatable')->name('webadmin.sales-presenter-pdf.reorder');
    Route::post('sales-presenter-pdf/reorder','Admin\SalesPresenterPdfController@updateOrder');

    //Partha
    Route::get('/exam/group-index', 'Admin\Exam\QuestionGroupController@index')->name('webadmin.questionGroupIndex');
    Route::get('/exam/group-add', 'Admin\Exam\QuestionGroupController@add')->name('webadmin.questionGroupAdd');
    Route::post('/exam/group-save', 'Admin\Exam\QuestionGroupController@save')->name('webadmin.questionGroupSave');
    Route::get('/exam/group-edit/{id}', 'Admin\Exam\QuestionGroupController@edit')->name('webadmin.questionGroupEdit');
    Route::post('/exam/group-update/{id}', 'Admin\Exam\QuestionGroupController@update')->name('webadmin.questionGroupUpdate');
    Route::get('/exam/group-delete/{id}', 'Admin\Exam\QuestionGroupController@delete')->name('webadmin.questionGroupDelete');

    Route::get('/exam/level-index', 'Admin\Exam\QuestionLevelController@index')->name('webadmin.questionLevelIndex');
    Route::get('/exam/level-add', 'Admin\Exam\QuestionLevelController@add')->name('webadmin.questionLevelAdd');
    Route::post('/exam/level-save', 'Admin\Exam\QuestionLevelController@save')->name('webadmin.questionLevelSave');
    Route::get('/exam/level-edit/{id}', 'Admin\Exam\QuestionLevelController@edit')->name('webadmin.questionLevelEdit');
    Route::post('/exam/level-update/{id}', 'Admin\Exam\QuestionLevelController@update')->name('webadmin.questionLevelUpdate');
    Route::get('/exam/level-delete/{id}', 'Admin\Exam\QuestionLevelController@delete')->name('webadmin.questionLevelDelete');

    Route::get('/exam/bank-index', 'Admin\Exam\QuestionBankController@index')->name('webadmin.questionBankIndex');
    Route::get('/exam/bank-add', 'Admin\Exam\QuestionBankController@add')->name('webadmin.questionBankAdd');
    Route::post('/exam/bank-save', 'Admin\Exam\QuestionBankController@save')->name('webadmin.questionBankSave');
    Route::get('/exam/bank-edit/{id}', 'Admin\Exam\QuestionBankController@edit')->name('webadmin.questionBankEdit');
    Route::post('/exam/bank-update/{id}', 'Admin\Exam\QuestionBankController@update')->name('webadmin.questionBankUpdate');
    Route::get('/exam/bank-delete/{id}', 'Admin\Exam\QuestionBankController@delete')->name('webadmin.questionBankDelete');
    Route::get('/exam/remove-explanation-image/{id}', 'Admin\Exam\QuestionBankController@removeExplanation')->name('webadmin.removeExplanation');


    // Route::group(['middleware' => 'verifyPermission'], function () {
    Route::get('/exam/onlineexam-index', 'Admin\Exam\OnlineExamController@index')->name('webadmin.onlineExamIndex');
    Route::get('/exam/report-index', 'Admin\Exam\ReportController@index')->name('webadmin.onlineReportIndex');
    Route::get('/exam/onlineexam-add', 'Admin\Exam\OnlineExamController@add')->name('webadmin.onlineExamAdd');
    Route::post('/exam/onlineexam-save', 'Admin\Exam\OnlineExamController@save')->name('webadmin.onlineExamSave');
    Route::get('/exam/onlineexam-edit/{id}', 'Admin\Exam\OnlineExamController@edit')->name('webadmin.onlineExamEdit');
    Route::post('/exam/onlineexam-update/{id}', 'Admin\Exam\OnlineExamController@update')->name('webadmin.onlineExamUpdate');
    Route::get('/exam/onlineexam-delete/{id}', 'Admin\Exam\OnlineExamController@delete')->name('webadmin.onlineExamDelete');
    Route::get('/exam/onlineexam-status/{id}/{flg}', 'Admin\Exam\OnlineExamController@status')->name('webadmin.onlineExamStatus');
    Route::get('/exam/onlineexam-add-question/{id}', 'Admin\Exam\OnlineExamController@addQuestion')->name('webadmin.onlineExamAddQuestion');
    

    //Marketing Banner Whatsapp
    Route::get('/whatsappshare/group-create', 'Admin\MarketingBannerShare@index')->name('webadmin.whatsapShareGroupCreate');
    Route::post('/whatsappshare/group-save', 'Admin\MarketingBannerShare@saveGroup')->name('webadmin.saveGroup');
    Route::get('/whatsappshare/group-index', 'Admin\MarketingBannerShare@groupIndex')->name('webadmin.groupIndex');
    Route::get('/whatsappshare/group-user/{id}', 'Admin\MarketingBannerShare@groupUserindex')->name('webadmin.groupUserindex');
    Route::post('/whatsappshare/group-user-remove', 'Admin\MarketingBannerShare@removeGroupUser')->name('webadmin.removeGroupUser');
    Route::get('/whatsappshare/test-send', 'Admin\MarketingBannerShare@sendFile')->name('webadmin.sendFile');
    Route::get('/whatsappshare/send-image-from', 'Admin\MarketingBannerShare@sendForm')->name('webadmin.sendForm');
    Route::post('/whatsappshare/send-to-group', 'Admin\MarketingBannerShare@saveToGroupMember')->name('webadmin.saveToGroupMember');
    
    Route::get('/notes', 'Admin\CalculatorNoteController@index')->name('webadmin.notes');
    Route::get('/notes/note-create', 'Admin\CalculatorNoteController@create')->name('webadmin.note-create');
    Route::post('/notes/note-store', 'Admin\CalculatorNoteController@store')->name('webadmin.note-store');
    Route::get('/notes/note-edit/{id}', 'Admin\CalculatorNoteController@edit')->name('webadmin.note-edit');
    Route::post('/notes/note-update/{id}', 'Admin\CalculatorNoteController@update')->name('webadmin.note-update');
    Route::get('/notes/note-destroy/{id}', 'Admin\CalculatorNoteController@destroy')->name('webadmin.note-destroy');
    
    //Webinar

    Route::get('/webinars', 'Admin\WebinarController@index')->name('webadmin.webinars');
    Route::get('/webinar-add', 'Admin\WebinarController@add')->name('webadmin.webinarAdd');
    Route::post('/webinar-save', 'Admin\WebinarController@save')->name('webadmin.webinarSave');
    Route::get('/webinar-edit/{id}', 'Admin\WebinarController@edit')->name('webadmin.webinarEdit');
    Route::post('/webinar-update/{id}', 'Admin\WebinarController@update')->name('webadmin.webinarUpdate');
    Route::get('/webinar-delete/{id}', 'Admin\WebinarController@delete')->name('webadmin.webinarDelete');

    Route::get('webinars/reorder','Admin\WebinarController@showDatatable')->name('webadmin.webinars.reorder');
    Route::post('webinars/reorder','Admin\WebinarController@updateOrder');
    
    //Home page Banners

    Route::get('/homepagebanners', 'Admin\HomepagebannerController@index')->name('webadmin.homepagebanners');
    Route::get('/homepagebanner-add', 'Admin\HomepagebannerController@add')->name('webadmin.homepagebannerAdd');
    Route::post('/homepagebanner-save', 'Admin\HomepagebannerController@save')->name('webadmin.homepagebannerSave');
    Route::get('/homepagebanner-edit/{id}', 'Admin\HomepagebannerController@edit')->name('webadmin.homepagebannerEdit');
    Route::post('/homepagebanner-update/{id}', 'Admin\HomepagebannerController@update')->name('webadmin.homepagebannerUpdate');
    Route::get('/homepagebanner-delete/{id}', 'Admin\HomepagebannerController@delete')->name('webadmin.homepagebannerDelete');

    Route::get('homepagebanners/reorder','Admin\HomepagebannerController@showDatatable')->name('webadmin.homepagebanners.reorder');
    Route::post('homepagebanners/reorder','Admin\HomepagebannerController@updateOrder');
    
    
    // MF Adviser Data
    Route::any('/mfadvisor-data', 'Admin\HomeController@mfadvisorData')->name('webadmin.mfadvisorData');

    // Homepage Membership Section
    Route::any('/homepagemembershipsData', 'Admin\HomepageMembershipController@homepagemembershipsData')->name('webadmin.homepagemembershipsData');

    // Homepage Membership Section Cards
    Route::get('/homepagememberships', 'Admin\HomepageMembershipController@index')->name('webadmin.homepagememberships');
    Route::get('/homepagemembership-add', 'Admin\HomepageMembershipController@add')->name('webadmin.homepagemembershipAdd');
    Route::post('/homepagemembership-save', 'Admin\HomepageMembershipController@save')->name('webadmin.homepagemembershipSave');
    Route::get('/homepagemembership-edit/{id}', 'Admin\HomepageMembershipController@edit')->name('webadmin.homepagemembershipEdit');
    Route::post('/homepagemembership-update/{id}', 'Admin\HomepageMembershipController@update')->name('webadmin.homepagemembershipUpdate');
    Route::get('/homepagemembership-delete/{id}', 'Admin\HomepageMembershipController@delete')->name('webadmin.homepagemembershipDelete');

    // Free Webinar Data
    Route::any('/freewebinar-data', 'Admin\HomeController@freewebinarData')->name('webadmin.freewebinarData');

    // Hear from Expert
    Route::get('/hearfromexperts', 'Admin\HearfromexpertController@index')->name('webadmin.hearfromexperts');
    Route::get('/hearfromexpert-add', 'Admin\HearfromexpertController@add')->name('webadmin.hearfromexpertAdd');
    Route::post('/hearfromexpert-save', 'Admin\HearfromexpertController@save')->name('webadmin.hearfromexpertSave');
    Route::get('/hearfromexpert-edit/{id}', 'Admin\HearfromexpertController@edit')->name('webadmin.hearfromexpertEdit');
    Route::post('/hearfromexpert-update/{id}', 'Admin\HearfromexpertController@update')->name('webadmin.hearfromexpertUpdate');
    Route::get('/hearfromexpert-delete/{id}', 'Admin\HearfromexpertController@delete')->name('webadmin.hearfromexpertDelete');

    Route::get('hearfromexperts/reorder','Admin\HearfromexpertController@showDatatable')->name('webadmin.hearfromexperts.reorder');
    Route::post('hearfromexperts/reorder','Admin\HearfromexpertController@updateOrder');

    // Footer Slides
    Route::get('/footerslides', 'Admin\FooterSlidesController@index')->name('webadmin.footerslides');
    Route::get('/footerslide-add', 'Admin\FooterSlidesController@add')->name('webadmin.footerslideAdd');
    Route::post('/footerslide-save', 'Admin\FooterSlidesController@save')->name('webadmin.footerslideSave');
    Route::get('/footerslide-edit/{id}', 'Admin\FooterSlidesController@edit')->name('webadmin.footerslideEdit');
    Route::post('/footerslide-update/{id}', 'Admin\FooterSlidesController@update')->name('webadmin.footerslideUpdate');
    Route::get('/footerslide-delete/{id}', 'Admin\FooterSlidesController@delete')->name('webadmin.footerslideDelete');

    Route::get('footerslides/reorder','Admin\FooterSlidesController@showDatatable')->name('webadmin.footerslides.reorder');
    Route::post('footerslides/reorder','Admin\FooterSlidesController@updateOrder');
    
    //Ask Brijesh

    Route::get('/askbrijesh', 'Admin\AskbrijeshController@index')->name('webadmin.askbrijesh');
    Route::get('/askbrijesh-edit/{id}', 'Admin\AskbrijeshController@edit')->name('webadmin.askbrijeshEdit');
    Route::post('/askbrijesh-update/{id}', 'Admin\AskbrijeshController@update')->name('webadmin.askbrijeshUpdate');
    Route::get('/askbrijesh-delete/{id}', 'Admin\AskbrijeshController@delete')->name('webadmin.askbrijeshDelete');
    
    // Export Askbrijesh
    Route::get('/askbrijesh/exportaskbrijesh','Admin\AskbrijeshController@exportaskbrijesh')->name('webadmin.askbrijesh.exportaskbrijesh');
    
    // Admin Logos
    
    Route::get('/adminlogo-index', 'Admin\AdminlogoController@index')->name('webadmin.adminlogoIndex');
    Route::get('/adminlogo-add', 'Admin\AdminlogoController@add')->name('webadmin.adminlogoAdd');
    Route::post('/adminlogo-save', 'Admin\AdminlogoController@save')->name('webadmin.adminlogoSave');
    Route::get('/adminlogo-edit/{id}', 'Admin\AdminlogoController@edit')->name('webadmin.adminlogoEdit');
    Route::post('/adminlogo-update/{id}', 'Admin\AdminlogoController@update')->name('webadmin.adminlogoUpdate');
    Route::get('/adminlogo-delete/{id}', 'Admin\AdminlogoController@delete')->name('webadmin.adminlogoDelete');
    
    //Website Tree

    Route::get('/website-tree', 'Admin\WebsitetreeController@index')->name('webadmin.websitetree');
    Route::get('/website-tree-add', 'Admin\WebsitetreeController@add')->name('webadmin.websitetreeAdd');
    Route::post('/website-tree-save', 'Admin\WebsitetreeController@save')->name('webadmin.websitetreeSave');
    Route::get('/website-tree-edit/{id}', 'Admin\WebsitetreeController@edit')->name('webadmin.websitetreeEdit');
    Route::post('/website-tree-update/{id}', 'Admin\WebsitetreeController@update')->name('webadmin.websitetreeUpdate');
    Route::get('/website-tree-delete/{id}', 'Admin\WebsitetreeController@delete')->name('webadmin.websitetreeDelete');
    
    Route::get('website-tree/reorder','Admin\WebsitetreeController@showDatatable')->name('webadmin.website-tree.reorder');
    Route::post('website-tree/reorder','Admin\WebsitetreeController@updateOrder');
    
    //Auto Renewal
    
    Route::get('/autorenewal-settings', 'Admin\AutorenewalController@edit')->name('webadmin.autorenewalEdit');
    Route::post('/autorenewal-update/{id}', 'Admin\AutorenewalController@update')->name('webadmin.autorenewalUpdate');
    
     Route::get('/asset-allocation/upload-questionaire', 'Admin\Asset_allocation_exam\AssetAllocationFileController@index')->name('webadmin.upload-questionaire');
    Route::post('/asset-allocation/questionaire-update/{id}', 'Admin\Asset_allocation_exam\AssetAllocationFileController@update')->name('webadmin.questionaire-update');
    
    Route::get('/asset-allocation/question', 'Admin\Asset_allocation_exam\AssetAllocationQuestionController@index')->name('webadmin.asset-allocation-question');
    Route::get('/asset-allocation/question-create', 'Admin\Asset_allocation_exam\AssetAllocationQuestionController@create')->name('webadmin.asset-allocation-question-create');
    Route::post('/asset-allocation/question-store', 'Admin\Asset_allocation_exam\AssetAllocationQuestionController@store')->name('webadmin.asset-allocation-question-store');

    Route::get('/asset-allocation/question-edit/{id}', 'Admin\Asset_allocation_exam\AssetAllocationQuestionController@edit')->name('webadmin.asset-allocation-question-edit');

    Route::post('/asset-allocation/question-update/{id}', 'Admin\Asset_allocation_exam\AssetAllocationQuestionController@update')->name('webadmin.asset-allocation-question-update');

    Route::get('/asset-allocation/question-destroy/{id}', 'Admin\Asset_allocation_exam\AssetAllocationQuestionController@destroy')->name('webadmin.asset-allocation-question-destroy');
    
    Route::get('/asset-allocation/score', 'Admin\Asset_allocation_exam\AssetAllocationScoreController@index')->name('webadmin.asset-allocation-score');

    Route::get('/asset-allocation/score-add', 'Admin\Asset_allocation_exam\AssetAllocationScoreController@create')->name('webadmin.asset-allocation-score-add');

    Route::post('/asset-allocation/score-store/', 'Admin\Asset_allocation_exam\AssetAllocationScoreController@store')->name('webadmin.asset-allocation-score-store');

    Route::get('/asset-allocation/score-edit/{id}', 'Admin\Asset_allocation_exam\AssetAllocationScoreController@edit')->name('webadmin.asset-allocation-score-edit');

    Route::post('/asset-allocation/score-update/{id}', 'Admin\Asset_allocation_exam\AssetAllocationScoreController@update')->name('webadmin.asset-allocation-score-update');

    Route::get('/asset-allocation/score-destroy/{id}', 'Admin\Asset_allocation_exam\AssetAllocationScoreController@destroy')->name('webadmin.asset-allocation-score-destroy');


    Route::get('/asset-allocation/products', 'Admin\Asset_allocation_exam\AssetAllocationProductController@index')->name('webadmin.asset-allocation-products');

    Route::get('/asset-allocation/product-add', 'Admin\Asset_allocation_exam\AssetAllocationProductController@create')->name('webadmin.asset-allocation-product-add');

    Route::post('/asset-allocation/product-store/', 'Admin\Asset_allocation_exam\AssetAllocationProductController@store')->name('webadmin.asset-allocation-product-store');

    Route::get('/asset-allocation/product-edit/{id}', 'Admin\Asset_allocation_exam\AssetAllocationProductController@edit')->name('webadmin.asset-allocation-product-edit');

    Route::post('/asset-allocation/product-update/{id}', 'Admin\Asset_allocation_exam\AssetAllocationProductController@update')->name('webadmin.asset-allocation-product-update');

    Route::get('/asset-allocation/product-destroy/{id}', 'Admin\Asset_allocation_exam\AssetAllocationProductController@destroy')->name('webadmin.asset-allocation-product-destroy');
    
    Route::get('/asset-allocation/groups', 'Admin\Asset_allocation_exam\SuggestedAssetAllocationController@index')->name('webadmin.asset-allocation-groups');
    Route::get('/asset-allocation/group-add', 'Admin\Asset_allocation_exam\SuggestedAssetAllocationController@create')->name('webadmin.asset-allocation-group-add');
    Route::post('/asset-allocation/group-store/', 'Admin\Asset_allocation_exam\SuggestedAssetAllocationController@store')->name('webadmin.asset-allocation-group-store');
    Route::get('/asset-allocation/group-edit/{id}', 'Admin\Asset_allocation_exam\SuggestedAssetAllocationController@edit')->name('webadmin.asset-allocation-group-edit');
    
    Route::post('/asset-allocation/group-update/{id}', 'Admin\Asset_allocation_exam\SuggestedAssetAllocationController@update')->name('webadmin.asset-allocation-group-update');
    
    Route::get('/asset-allocation/group-destroy/{id}', 'Admin\Asset_allocation_exam\SuggestedAssetAllocationController@destroy')->name('webadmin.asset-allocation-group-destroy');
    
    Route::get('/asset-allocation/periods', 'Admin\Asset_allocation_exam\InvestmentPeriodController@index')->name('webadmin.asset-allocation-periods');
    Route::get('/asset-allocation/period-add', 'Admin\Asset_allocation_exam\InvestmentPeriodController@create')->name('webadmin.asset-allocation-period-add');
    Route::post('/asset-allocation/period-store/', 'Admin\Asset_allocation_exam\InvestmentPeriodController@store')->name('webadmin.asset-allocation-period-store');
    Route::get('/asset-allocation/period-edit/{id}', 'Admin\Asset_allocation_exam\InvestmentPeriodController@edit')->name('webadmin.asset-allocation-period-edit');
    Route::post('/asset-allocation/period-update/{id}', 'Admin\Asset_allocation_exam\InvestmentPeriodController@update')->name('webadmin.asset-allocation-period-update');
    Route::get('/asset-allocation/period-destroy/{id}', 'Admin\Asset_allocation_exam\InvestmentPeriodController@destroy')->name('webadmin.asset-allocation-period-destroy');
    
    Route::get('/asset-allocation/user-define-asset-class', 'Admin\Asset_allocation_exam\UserDefineAssetClassController@index')->name('webadmin.asset-allocation-user-define-asset-class');
    Route::get('/asset-allocation/user-define-asset-class-add', 'Admin\Asset_allocation_exam\UserDefineAssetClassController@create')->name('webadmin.user-define-asset-class-add');
    Route::post('/asset-allocation/user-define-asset-class-store/', 'Admin\Asset_allocation_exam\UserDefineAssetClassController@store')->name('webadmin.user-define-asset-class-store');
     Route::get('/asset-allocation/user-define-asset-class-edit/{id}', 'Admin\Asset_allocation_exam\UserDefineAssetClassController@edit')->name('webadmin.user-define-asset-class-edit');
    Route::post('/asset-allocation/user-define-asset-class-update/{id}', 'Admin\Asset_allocation_exam\UserDefineAssetClassController@update')->name('webadmin.user-define-asset-class-update');
    Route::get('/asset-allocation/user-define-asset-class-destroy/{id}', 'Admin\Asset_allocation_exam\UserDefineAssetClassController@destroy')->name('webadmin.user-define-asset-class-destroy');

    Route::get('/updateaccorddata', 'Admin\AccordController@index')->name('webadmin.updateaccorddata');
    Route::post('/saveaccorddata', 'Admin\AccordController@saveaccorddata')->name('webadmin.saveaccorddata');
    Route::get('/accordupdatedata', 'Admin\AccordController@accordupdatedata')->name('webadmin.accordupdatedata');
    Route::get('/updateaccordtable/{file_name}', 'Admin\AccordController@updateaccordtable')->name('webadmin.updateaccordtable');
    Route::get('/downloadaccordtable/{file_name}', 'Admin\AccordController@downloadaccordtable')->name('webadmin.downloadaccordtable');
    Route::get('/viewaccord', 'Admin\AccordController@viewaccord')->name('webadmin.viewaccord');
    Route::get('/viewaccordtable/{id}', 'Admin\AccordController@viewaccordtable')->name('webadmin.viewaccordtable');
    Route::get('/shortcategory', 'Admin\AccordController@shortcategory')->name('webadmin.shortcategory');
    Route::get('/short-category-delete/{id}', 'Admin\AccordController@short_category_delete')->name('webadmin.short_category_delete');
    Route::post('/short-category-add-edit', 'Admin\AccordController@short_category_add_edit')->name('webadmin.short_category_add_edit');

    Route::get('/ratting', 'Admin\AccordController@ratting')->name('webadmin.ratting');
    Route::get('/ratting-sync', 'Admin\AccordController@ratting_sync')->name('webadmin.ratting_sync');
    Route::get('/ratting-delete/{id}', 'Admin\AccordController@ratting_delete')->name('webadmin.ratting_delete');
    Route::post('/ratting-add-edit', 'Admin\AccordController@ratting_add_edit')->name('webadmin.ratting_add_edit');

    Route::get('/database-backup', 'Admin\DatabasebackupController@index')->name('webadmin.databasebackup');
    Route::get('/database-backup-now', 'Admin\DatabasebackupController@databasebackupnow')->name('webadmin.databasebackupnow');
    Route::get('/database-backup-delete/{id}', 'Admin\DatabasebackupController@databasebackupdelete')->name('webadmin.databasebackupDelete');

    Route::get('/nps', 'Admin\NpsController@index')->name('webadmin.nps');
    Route::post('/update-nps', 'Admin\NpsController@updatenps')->name('webadmin.update-nps');

    Route::get('/package-creation-type', 'Admin\PackageCreationController@package_creation_type')->name('webadmin.package_creation_type');
    Route::get('/package-creation-type-add', 'Admin\PackageCreationController@package_creation_type_add')->name('webadmin.package_creation_type_add');
    Route::post('/package-creation-type-save', 'Admin\PackageCreationController@package_creation_type_save')->name('webadmin.package_creation_type_save');
    Route::get('/package-creation-type-edit/{id}', 'Admin\PackageCreationController@package_creation_type_edit')->name('webadmin.package_creation_type_edit');
    Route::post('/package-creation-type-update/{id}', 'Admin\PackageCreationController@package_creation_type_update')->name('webadmin.package_creation_type_update');
    Route::get('/package-creation-type-delete/{id}', 'Admin\PackageCreationController@package_creation_type_delete')->name('webadmin.package_creation_type_delete');
    Route::get('/package-creation-type-down/{id}', 'Admin\PackageCreationController@package_creation_type_down')->name('webadmin.package_creation_type_down');
    Route::get('/package-creation-type-up/{id}', 'Admin\PackageCreationController@package_creation_type_up')->name('webadmin.package_creation_type_up');

    Route::get('/package-creation-dropdown', 'Admin\PackageCreationController@package_creation_dropdown')->name('webadmin.package_creation_dropdown');
    Route::get('/package-creation-dropdown-add', 'Admin\PackageCreationController@package_creation_dropdown_add')->name('webadmin.package_creation_dropdown_add');
    Route::post('/package-creation-dropdown-save', 'Admin\PackageCreationController@package_creation_dropdown_save')->name('webadmin.package_creation_dropdown_save');
    Route::get('/package-creation-dropdown-edit/{id}', 'Admin\PackageCreationController@package_creation_dropdown_edit')->name('webadmin.package_creation_dropdown_edit');
    Route::post('/package-creation-dropdown-update/{id}', 'Admin\PackageCreationController@package_creation_dropdown_update')->name('webadmin.package_creation_dropdown_update');
    Route::get('/package-creation-dropdown-delete/{id}', 'Admin\PackageCreationController@package_creation_dropdown_delete')->name('webadmin.package_creation_dropdown_delete');
    Route::get('/package-creation-hint', 'Admin\PackageCreationController@package_creation_hint')->name('webadmin.package_creation_hint');
    Route::post('/package-creation-hint-update', 'Admin\PackageCreationController@package_creation_dropdown_hint_update')->name('webadmin.package_creation_dropdown_hint_update');

    Route::get('/package-creation-setting', 'Admin\PackageCreationController@package_creation_setting')->name('webadmin.package_creation_setting');
    Route::post('/package-creation-setting-update', 'Admin\PackageCreationController@package_creation_setting_update')->name('webadmin.package_creation_setting_update');

    Route::get('/user-subscription', 'Admin\UserSubscriptionController@index')->name('webadmin.user_subscription');

    Route::get('/analytics-live-users', 'Admin\AnalyticsController@livemember')->name('webadmin.analyticslivemember');
    Route::get('/analytics-inactive-users', 'Admin\AnalyticsController@haventlogged')->name('webadmin.analyticsinactiveusers');
    Route::get('/analytics-report', 'Admin\AnalyticsController@reportPage')->name('webadmin.analyticsreport');

    Route::get('/referral-point', 'Admin\ReferralCodeController@index')->name('webadmin.referral_code_list');
    Route::get('/referral-point-add', 'Admin\ReferralCodeController@add')->name('webadmin.referral_code_add');
    Route::get('/referral-point-delete/{id}', 'Admin\ReferralCodeController@delete')->name('webadmin.userReferralCodeDelete');
    Route::post('/referral-point-save', 'Admin\ReferralCodeController@save')->name('webadmin.referral_code_save');
    Route::get('/referral-point-setting', 'Admin\ReferralCodeController@setting')->name('webadmin.referral_code_setting');
    Route::get('/referral-point-setting-edit/{id}', 'Admin\ReferralCodeController@settingedit')->name('webadmin.edit_referral_code_setting');
    Route::post('/referral-point-setting-update', 'Admin\ReferralCodeController@settingupdate')->name('webadmin.referral_code_setting_update');

    Route::get('/famous-quotes', 'Admin\FamousQuotesController@index')->name('webadmin.famousQuotes');
    Route::get('/famous-quotes-add', 'Admin\FamousQuotesController@add')->name('webadmin.famousQuotesAdd');
    Route::post('/famous-quotes-save', 'Admin\FamousQuotesController@save')->name('webadmin.famousQuotesSave');
    Route::get('/famous-quotes-edit/{id}', 'Admin\FamousQuotesController@edit')->name('webadmin.famousQuotesEdit');
    Route::post('/famous-quotes-update/{id}', 'Admin\FamousQuotesController@update')->name('webadmin.famousQuotesUpdate');
    Route::get('/famous-quotes-delete/{id}', 'Admin\FamousQuotesController@delete')->name('webadmin.famousQuotesDelete');
    Route::get('/famous-quotes-image/{id}', 'Admin\FamousQuotesController@image')->name('webadmin.famousQuotesImage');
    Route::get('/famous-quotes-image-delete/{id}', 'Admin\FamousQuotesController@deleteImage')->name('webadmin.famousQuotesImageDelete');
    Route::post('/famous-quotes-image-save', 'Admin\FamousQuotesController@imagesave')->name('webadmin.famousQuotesImageSave');

    Route::get('/corporate-users', 'Admin\CorporateUsersController@index')->name('webadmin.corporateUsers');
    
    Route::get('/mf-research-notes', 'Admin\MfresearchController@index')->name('webadmin.mf-research-notes');
    Route::get('/mf-research-master', 'Admin\MfresearchController@master')->name('webadmin.mf-research-master');
    Route::get('/mf-research-master-export', 'Admin\MfresearchController@master_export')->name('webadmin.mf-research-master-export');
    Route::get('/mf-research-master-cron', 'Admin\MfresearchController@master_cron')->name('webadmin.mf-research-master-cron');
    Route::get('/mf-research-master-delete', 'Admin\MfresearchController@master_delete')->name('webadmin.mf-research-master-delete');
    Route::get('/mf-research-change-status/{id}', 'Admin\MfresearchController@master_change_status')->name('webadmin.mf-research-change-status');
    Route::get('/mf-research/note-edit/{id}', 'Admin\MfresearchController@edit')->name('webadmin.mf-research-note-edit');
    Route::post('/mf-research/note-update/{id}', 'Admin\MfresearchController@update')->name('webadmin.mf-research-note-update');


    Route::get('/mf-research-avg', 'Admin\MfresearchController@avg')->name('webadmin.mf-research-avg');
    Route::get('/mf-research-avg-export', 'Admin\MfresearchController@avg_export')->name('webadmin.mf-research-avg-export');

    Route::get('/mf-category-wise-performance', 'Admin\MfresearchController@mf_category_wise_performance')->name('webadmin.mf-category-wise-performance');
    Route::post('/mf-category-wise-performance-upload', 'Admin\MfresearchController@mf_category_wise_performance_upload')->name('webadmin.mf-category-wise-performance-upload');

    Route::get('/mf-research-plan', 'Admin\MfresearchController@plan')->name('webadmin.mf-research-plan');
    Route::post('/mf-research-plan-upload', 'Admin\MfresearchController@upload_plan')->name('webadmin.mf-research-plan-upload');
    Route::get('/mf-research-plan-status/{id}', 'Admin\MfresearchController@status_plan')->name('webadmin.mf-research-plan-status');

    Route::get('/mf-research-category', 'Admin\MfresearchController@category')->name('webadmin.mf-research-category');
    Route::post('/mf-research-category-upload', 'Admin\MfresearchController@upload_category')->name('webadmin.mf-research-category-upload');
    Route::get('/mf-research-category-status/{id}', 'Admin\MfresearchController@status_category')->name('webadmin.mf-research-category-status');
    Route::get('/mf-research-category-order','Admin\MfresearchController@showDatatable')->name('webadmin.mf-research-category-order');
    Route::post('mf-research-category-order-update','Admin\MfresearchController@updateOrder');

    Route::get('/dynamic-email/index', 'Admin\DynamicemailController@index')->name('webadmin.dynamic-email-index');
    Route::get('/dynamic-email/edit/{id}', 'Admin\DynamicemailController@edit')->name('webadmin.dynamic-email-edit');
    Route::post('/dynamic-email/update/{id}', 'Admin\DynamicemailController@update')->name('webadmin.dynamic-email-update');

    Route::get('/mf-research/navhist', 'Admin\NavhistController@index')->name('webadmin.navhistIndex');
    Route::get('/mf-research/navhist-add', 'Admin\NavhistController@add')->name('webadmin.navhistAdd');
    Route::post('/mf-research/navhist-save', 'Admin\NavhistController@save')->name('webadmin.navhistSave');
    Route::get('/mf-research/navhist-edit/{id}', 'Admin\NavhistController@edit')->name('webadmin.navhistEdit');
    Route::post('/mf-research/navhist-update/{id}', 'Admin\NavhistController@update')->name('webadmin.navhistUpdate');
    Route::get('/mf-research/navhist-delete/{id}', 'Admin\NavhistController@delete')->name('webadmin.navhistDelete');
    

    Route::get('product/product', 'Admin\ProductController@index')->name('webadmin.product');
    Route::get('product/product-add', 'Admin\ProductController@add')->name('webadmin.productAdd');
    Route::post('product/product-save', 'Admin\ProductController@save')->name('webadmin.productSave');
    Route::get('product/product-edit/{id}', 'Admin\ProductController@edit')->name('webadmin.productEdit');
    Route::post('product/product-update/{id}', 'Admin\ProductController@update')->name('webadmin.productUpdate');
    Route::get('product/product-delete/{id}', 'Admin\ProductController@delete')->name('webadmin.productDelete');

    Route::get('product/product-type', 'Admin\ProductController@indexProductType')->name('webadmin.productType');
    Route::get('product/product-type-add', 'Admin\ProductController@addProductType')->name('webadmin.productTypeAdd');
    Route::post('product/product-type-save', 'Admin\ProductController@saveProductType')->name('webadmin.productTypeSave');
    Route::get('product/product-type-edit/{id}', 'Admin\ProductController@editProductType')->name('webadmin.productTypeEdit');
    Route::post('product/product-type-update/{id}', 'Admin\ProductController@updateProductType')->name('webadmin.productTypeUpdate');
    Route::get('product/product-type-delete/{id}', 'Admin\ProductController@deleteProductType')->name('webadmin.productTypeDelete');
    
    Route::get('mf-portfolio', 'Admin\MfPortfolioController@index')->name('webadmin.mfPortfolio');
    Route::get('mf-portfolio-add', 'Admin\MfPortfolioController@add')->name('webadmin.mfPortfolioAdd');
    Route::post('mf-portfolio-save', 'Admin\MfPortfolioController@save')->name('webadmin.mfPortfolioSave');
    Route::get('mf-portfolio-edit/{id}', 'Admin\MfPortfolioController@edit')->name('webadmin.mfPortfolioEdit');
    Route::post('mf-portfolio-update/{id}', 'Admin\MfPortfolioController@update')->name('webadmin.mfPortfolioUpdate');
    Route::get('mf-portfolio-delete/{schemecode}/{invdate}/{srno}', 'Admin\MfPortfolioController@delete')->name('webadmin.mfPortfolioDelete');
    Route::get('mf-portfolio-delete-all/{start_date}/{end_date}', 'Admin\MfPortfolioController@deleteAll')->name('webadmin.mfPortfolioDeleteAll');

    Route::get('mf-portfolio-analysis', 'Admin\MfPortfolioController@indexAnalysis')->name('webadmin.mfPortfolioAnalysis');
    Route::get('mf-portfolio-analysis-add', 'Admin\MfPortfolioController@addAnalysis')->name('webadmin.mfPortfolioAnalysisAdd');
    Route::post('mf-portfolio-analysis-save', 'Admin\MfPortfolioController@saveAnalysis')->name('webadmin.mfPortfolioAnalysisSave');
    Route::get('mf-portfolio-analysis-edit/{id}', 'Admin\MfPortfolioController@editAnalysis')->name('webadmin.mfPortfolioAnalysisEdit');
    Route::post('mf-portfolio-analysis-update/{id}', 'Admin\MfPortfolioController@updateAnalysis')->name('webadmin.mfPortfolioAnalysisUpdate');
    Route::get('mf-portfolio-analysis-delete/{id}', 'Admin\MfPortfolioController@deleteAnalysis')->name('webadmin.mfPortfolioAnalysisDelete');
    
    Route::get('/capital-gains-tax-calculator/index', 'Admin\CapitalGainsCalculatorController@index')->name('webadmin.CapitalGainsCalculatorIndex');
    Route::get('/capital-gains-tax-calculator/add', 'Admin\CapitalGainsCalculatorController@add')->name('webadmin.CapitalGainsCalculatorAdd');
    Route::post('/capital-gains-tax-calculator/save', 'Admin\CapitalGainsCalculatorController@save')->name('webadmin.CapitalGainsCalculatorSave');
    Route::get('/capital-gains-tax-calculator/edit/{id}', 'Admin\CapitalGainsCalculatorController@edit')->name('webadmin.CapitalGainsCalculatorEdit');
    Route::post('/capital-gains-tax-calculator/update/{id}', 'Admin\CapitalGainsCalculatorController@update')->name('webadmin.CapitalGainsCalculatorUpdate');
    Route::get('/capital-gains-tax-calculator/delete/{id}', 'Admin\CapitalGainsCalculatorController@delete')->name('webadmin.CapitalGainsCalculatorDelete');

    
    Route::get('/capital-gains-tax-rate/index', 'Admin\CapitalGainsCalculatorController@indexTaxRate')->name('webadmin.CapitalGainsTaxRateIndex');
    Route::get('/capital-gains-tax-rate/add', 'Admin\CapitalGainsCalculatorController@addTaxRate')->name('webadmin.CapitalGainsTaxRateAdd');
    Route::post('/capital-gains-tax-rate/save', 'Admin\CapitalGainsCalculatorController@saveTaxRate')->name('webadmin.CapitalGainsTaxRateSave');
    Route::get('/capital-gains-tax-rate/edit/{id}', 'Admin\CapitalGainsCalculatorController@editTaxRate')->name('webadmin.CapitalGainsTaxRateEdit');
    Route::post('/capital-gains-tax-rate/update/{id}', 'Admin\CapitalGainsCalculatorController@updateTaxRate')->name('webadmin.CapitalGainsTaxRateUpdate');
    Route::get('/capital-gains-tax-rate/delete/{id}', 'Admin\CapitalGainsCalculatorController@deleteTaxRate')->name('webadmin.CapitalGainsTaxRateDelete');
    
    Route::get('/calculator-category/index', 'Admin\CalculatorCategoryController@index')->name('webadmin.calculatorCategoryIndex');
    Route::get('/calculator-category/add', 'Admin\CalculatorCategoryController@add')->name('webadmin.calculatorCategoryAdd');
    Route::post('/calculator-category/save', 'Admin\CalculatorCategoryController@save')->name('webadmin.calculatorCategorySave');
    Route::get('/calculator-category/edit/{id}', 'Admin\CalculatorCategoryController@edit')->name('webadmin.calculatorCategoryEdit');
    Route::post('/calculator-category/update/{id}', 'Admin\CalculatorCategoryController@update')->name('webadmin.calculatorCategoryUpdate');
    Route::get('/calculator-category/delete/{id}', 'Admin\CalculatorCategoryController@delete')->name('webadmin.calculatorCategoryDelete');
    Route::get('calculator-category/reorder','Admin\CalculatorCategoryController@calculatorCategoryReorder')->name('webadmin.calculatorCategoryReorder');
    Route::post('calculator-category/reorder','Admin\CalculatorCategoryController@calculatorCategoryReorderUpdate');

    Route::get('/calculator/index', 'Admin\CalculatorCategoryController@calculator')->name('webadmin.calculatorIndex');
    Route::get('/calculator/add', 'Admin\CalculatorCategoryController@calculatorAdd')->name('webadmin.calculatorAdd');
    Route::post('/calculator/save', 'Admin\CalculatorCategoryController@calculatorSave')->name('webadmin.calculatorSave');
    Route::get('/calculator/edit/{id}', 'Admin\CalculatorCategoryController@calculatorEdit')->name('webadmin.calculatorEdit');
    Route::post('/calculator/update/{id}', 'Admin\CalculatorCategoryController@calculatorUpdate')->name('webadmin.calculatorUpdate');
    Route::get('/calculator/delete/{id}', 'Admin\CalculatorCategoryController@calculatorDelete')->name('webadmin.calculatorDelete');
    Route::get('/calculator/remove-how-to/{id}', 'Admin\CalculatorCategoryController@removeHowTo')->name('webadmin.calculatorRemoveHowTo');
    Route::get('/calculator/remove-case-study/{id}', 'Admin\CalculatorCategoryController@removeCaseStudy')->name('webadmin.calculatorRemoveCaseStudy');
    Route::get('calculator/reorder/{id}','Admin\CalculatorCategoryController@showDatatable')->name('webadmin.calculator_reorder');
    Route::post('calculator/reorder','Admin\CalculatorCategoryController@updateOrder');

    Route::get('/mf-research/index', 'Admin\MfresearchController@mfResearch')->name('webadmin.mfResearchIndex');
    Route::get('/mf-research/add', 'Admin\MfresearchController@mfResearchAdd')->name('webadmin.mfResearchAdd');
    Route::post('/mf-research/save', 'Admin\MfresearchController@mfResearchSave')->name('webadmin.mfResearchSave');
    Route::get('/mf-research/edit/{id}', 'Admin\MfresearchController@mfResearchEdit')->name('webadmin.mfResearchEdit');
    Route::post('/mf-research/update/{id}', 'Admin\MfresearchController@mfResearchUpdate')->name('webadmin.mfResearchUpdate');
    Route::get('/mf-research/delete/{id}', 'Admin\MfresearchController@mfResearchDelete')->name('webadmin.mfResearchDelete');
    Route::get('mf-research/reorder','Admin\MfresearchController@mfResearchReorder')->name('webadmin.mfResearchReorder');
    Route::post('mf-research/reorder','Admin\MfresearchController@mfResearchReorderUpdate');
    
    Route::get('/previous-webinar-index', 'Admin\PreviousWebinarController@index')->name('webadmin.previous_webinarIndex');
    Route::get('/previous-webinar-add', 'Admin\PreviousWebinarController@add')->name('webadmin.previous_webinarAdd');
    Route::post('/previous-webinar-save', 'Admin\PreviousWebinarController@save')->name('webadmin.previous_webinarSave');
    Route::get('/previous-webinar-edit/{id}', 'Admin\PreviousWebinarController@edit')->name('webadmin.previous_webinarEdit');
    Route::post('/previous-webinar-update/{id}', 'Admin\PreviousWebinarController@update')->name('webadmin.previous_webinarUpdate');
    Route::get('/previous-webinar-delete/{id}', 'Admin\PreviousWebinarController@delete')->name('webadmin.previous_webinarDelete');
    Route::get('previous-webinar/reorder','Admin\PreviousWebinarController@showDatatable')->name('webadmin.previous_webinar.reorder');
    Route::post('previous-webinar/reorder','Admin\PreviousWebinarController@updateOrder');
    
    Route::get('/package/renewal', 'Admin\PackageCreationController@renewal')->name('webadmin.PackageRenewalIndex');
    Route::get('/package/renewal-edit/{id}', 'Admin\PackageCreationController@renewalEdit')->name('webadmin.PackageRenewalEdit');
    Route::post('/package/renewal-update/{id}', 'Admin\PackageCreationController@renewalUpdate')->name('webadmin.PackageRenewalUpdate');

    
    //Category
    Route::get('/salespresenterpdfcategory', 'Admin\SalespresenterPdfCategoryController@category_index')->name('webadmin.salespresenterpdfcategory');
    Route::get('/salespresenterpdfcategory-add', 'Admin\SalespresenterPdfCategoryController@category_add')->name('webadmin.salespresenterpdfcategoryAdd');
    Route::post('/salespresenterpdfcategory-save', 'Admin\SalespresenterPdfCategoryController@category_save')->name('webadmin.salespresenterpdfcategorySave');
    Route::get('/salespresenterpdfcategory-edit/{id}', 'Admin\SalespresenterPdfCategoryController@category_edit')->name('webadmin.salespresenterpdfcategoryEdit');
    Route::post('/salespresenterpdfcategory-update/{id}', 'Admin\SalespresenterPdfCategoryController@category_update')->name('webadmin.salespresenterpdfcategoryUpdate');
    Route::get('/salespresenterpdfcategory-delete/{id}', 'Admin\SalespresenterPdfCategoryController@category_delete')->name('webadmin.salespresenterpdfcategoryDelete');


    Route::get('client-communication', 'Admin\ClientCommController@index')->name('webadmin.clientCommunication');
    Route::get('client-communication/add', 'Admin\ClientCommController@add')->name('webadmin.clientCommunicationAdd');
    Route::post('client-communication/save', 'Admin\ClientCommController@save')->name('webadmin.clientCommunicationSave');
    Route::get('client-communication/edit/{id}', 'Admin\ClientCommController@edit')->name('webadmin.clientCommunicationEdit');
    Route::post('client-communication/update/{id}', 'Admin\ClientCommController@update')->name('webadmin.clientCommunicationUpdate');
    Route::get('client-communication/delete/{id}', 'Admin\ClientCommController@delete')->name('webadmin.clientCommunicationDelete');
    
    Route::get('client-communication/reorder','Admin\ClientCommController@showDatatable')->name('webadmin.communication.reorder');
    Route::post('client-communication/reorder','Admin\ClientCommController@updateOrder');

    Route::get('notification', 'Admin\NotificationController@index')->name('webadmin.notification');
    Route::get('notification/add', 'Admin\NotificationController@add')->name('webadmin.notificationAdd');
    Route::post('notification/save', 'Admin\NotificationController@save')->name('webadmin.notificationSave');
    Route::get('notification/edit/{id}', 'Admin\NotificationController@edit')->name('webadmin.notificationEdit');
    Route::post('notification/update/{id}', 'Admin\NotificationController@update')->name('webadmin.notificationUpdate');
    Route::get('notification/delete/{id}', 'Admin\NotificationController@delete')->name('webadmin.notificationDelete');
    
    Route::get('notification/reorder','Admin\NotificationController@showDatatable')->name('webadmin.notification.reorder');
    Route::post('notification/reorder','Admin\NotificationController@updateOrder');

    //Category
    Route::get('/client-ommunication-category', 'Admin\ClientCommCategoryController@category_index')->name('webadmin.clientCommunicationcategory');
    Route::get('/client-ommunication-category-add', 'Admin\ClientCommCategoryController@category_add')->name('webadmin.clientCommunicationcategoryAdd');
    Route::post('/client-ommunication-category-save', 'Admin\ClientCommCategoryController@category_save')->name('webadmin.clientCommunicationcategorySave');
    Route::get('/client-ommunication-category-edit/{id}', 'Admin\ClientCommCategoryController@category_edit')->name('webadmin.clientCommunicationcategoryEdit');
    Route::post('/client-ommunication-category-update/{id}', 'Admin\ClientCommCategoryController@category_update')->name('webadmin.clientCommunicationcategoryUpdate');
    Route::get('/client-ommunication-category-delete/{id}', 'Admin\ClientCommCategoryController@category_delete')->name('webadmin.clientCommunicationcategoryDelete');

    Route::get('/home/mso-associate', 'Admin\HomeController@businessAssociate')->name('webadmin.businessAssociateList');
    Route::get('/home/mso-associate-delete/{id}', 'Admin\HomeController@businessAssociateDelete')->name('webadmin.businessAssociateDelete');
    
    
    Route::get('premiumbanner/premiumbannertag', 'Admin\PremiumbannerController@tag_index')->name('webadmin.premiumbannertag');
    Route::get('premiumbanner/premiumbannertag-add', 'Admin\PremiumbannerController@tag_add')->name('webadmin.premiumbannertagAdd');
    Route::post('premiumbanner/premiumbannertag-save', 'Admin\PremiumbannerController@tag_save')->name('webadmin.premiumbannertagSave');
    Route::get('premiumbanner/premiumbannertag-edit/{id}', 'Admin\PremiumbannerController@tag_edit')->name('webadmin.premiumbannertagEdit');
    Route::post('premiumbanner/premiumbannertag-update/{id}', 'Admin\PremiumbannerController@tag_update')->name('webadmin.premiumbannertagUpdate');
    Route::get('premiumbanner/premiumbannertag-delete/{id}', 'Admin\PremiumbannerController@tag_delete')->name('webadmin.premiumbannertagDelete');
    
    Route::get('/readymade-portfolio/readymade-index', 'Admin\ReadymadePortfolioController@index')->name('webadmin.readymadeIndex');
	Route::get('/readymade-portfolio/readymade-add', 'Admin\ReadymadePortfolioController@add')->name('webadmin.readymadeAdd');
	Route::post('/readymade-portfolio/readymade-save', 'Admin\ReadymadePortfolioController@save')->name('webadmin.readymadeSave');
	Route::get('/readymade-portfolio/readymade-edit/{id}', 'Admin\ReadymadePortfolioController@edit')->name('webadmin.readymadeEdit');
	Route::post('/readymade-portfolio/readymade-update/{id}', 'Admin\ReadymadePortfolioController@update')->name('webadmin.readymadeUpdate');
	Route::get('/readymade-portfolio/readymade-delete/{id}', 'Admin\ReadymadePortfolioController@delete')->name('webadmin.readymadeDelete');


    Route::get('readymade-portfolio/reorder','Admin\ReadymadePortfolioController@showDatatable')->name('webadmin.readymadeReorder');
    Route::post('readymade-portfolio/reorder-update','Admin\ReadymadePortfolioController@updateOrder');

    Route::get('/readymade-portfolio/category', 'Admin\ReadymadePortfolioCategoryController@index')->name('webadmin.readymadePortfolioCategory');
    Route::get('/readymade-portfolio/category-add', 'Admin\ReadymadePortfolioCategoryController@add')->name('webadmin.readymadePortfolioCategoryAdd');
    Route::post('/readymade-portfolio/category-save', 'Admin\ReadymadePortfolioCategoryController@save')->name('webadmin.readymadePortfolioCategorySave');
    Route::get('/readymade-portfolio/category-edit/{id}', 'Admin\ReadymadePortfolioCategoryController@edit')->name('webadmin.readymadePortfolioCategoryEdit');
    Route::post('/readymade-portfolio/category-update/{id}', 'Admin\ReadymadePortfolioCategoryController@update')->name('webadmin.readymadePortfolioCategoryUpdate');
    Route::get('/readymade-portfolio/category-delete/{id}', 'Admin\ReadymadePortfolioCategoryController@delete')->name('webadmin.readymadePortfolioCategoryDelete');

	// Readymade Data
	Route::get('/readymade-portfolio/{task}/index', 'Admin\ReadymadeDataController@index')->name('webadmin.readymadeportfolioIndex');
	Route::get('/readymade-portfolio/{task}/add', 'Admin\ReadymadeDataController@add')->name('webadmin.readymadeportfolioAdd');
	Route::post('/readymade-portfolio/save', 'Admin\ReadymadeDataController@save')->name('webadmin.readymadeportfolioSave');
	Route::get('/readymade-portfolio/edit/{id}', 'Admin\ReadymadeDataController@edit')->name('webadmin.readymadeportfolioEdit');
	Route::post('/readymade-portfolio/update/{id}', 'Admin\ReadymadeDataController@update')->name('webadmin.readymadeportfolioUpdate');
	Route::get('/readymade-portfolio/delete/{id}', 'Admin\ReadymadeDataController@delete')->name('webadmin.readymadeportfolioDelete');
    
    Route::get('marketingvideos/mvtag', 'Admin\MarketingvideoController@tag_index')->name('webadmin.mvtag');
    Route::get('marketingvideos/mvtag-add', 'Admin\MarketingvideoController@tag_add')->name('webadmin.mvtagAdd');
    Route::post('marketingvideos/mvtag-save', 'Admin\MarketingvideoController@tag_save')->name('webadmin.mvtagSave');
    Route::get('marketingvideos/mvtag-edit/{id}', 'Admin\MarketingvideoController@tag_edit')->name('webadmin.mvtagEdit');
    Route::post('marketingvideos/mvtag-update/{id}', 'Admin\MarketingvideoController@tag_update')->name('webadmin.mvtagUpdate');
    Route::get('marketingvideos/mvtag-delete/{id}', 'Admin\MarketingvideoController@tag_delete')->name('webadmin.mvtagDelete');
    
    Route::get('/mf-disclaimer', 'Admin\MfresearchController@disclaimer')->name('webadmin.mf-disclaimer');
    Route::post('/mf-disclaimer-update', 'Admin\MfresearchController@updateDisclaimer')->name('webadmin.mf-disclaimer-update');

    //National Conference
    Route::get('/national-conference', 'Admin\NationalConference@index')->name('webadmin.national_conference');
    Route::get('/national-conference-csv', 'Admin\NationalConference@csvDownload')->name('webadmin.national_conference_csv');
    
     Route::get('/calculator-footer/add', 'Admin\CalculatorFooterController@add')->name('webadmin.calculatorFooterAdd');
     Route::get('/calculator-footer/index', 'Admin\CalculatorFooterController@index')->name('webadmin.calculatorFooterIndex');
     Route::post('/calculator-footer/save', 'Admin\CalculatorFooterController@save')->name('webadmin.calculatorFooterSave');
     
    Route::get('/welcome-letter', 'Admin\WelcomeLetterController@index')->name('webadmin.welcomeletters');
    Route::get('/welcomeletter-add', 'Admin\WelcomeLetterController@add')->name('webadmin.welcomeletterAdd');
    Route::post('/welcomeletter-save', 'Admin\WelcomeLetterController@save')->name('webadmin.welcomeletterSave');
    Route::get('/welcomeletter-edit/{id}', 'Admin\WelcomeLetterController@edit')->name('webadmin.welcomeletterEdit');
    Route::post('/welcomeletter-update/{id}', 'Admin\WelcomeLetterController@update')->name('webadmin.welcomeletterUpdate');
    Route::get('/welcomeletter-delete/{id}', 'Admin\WelcomeLetterController@delete')->name('webadmin.welcomeletterDelete');
    Route::get('/welcomeletter-setting', 'Admin\WelcomeLetterController@setting')->name('webadmin.welcomeletterSetting');

    Route::get('welcomeletters/reorder','Admin\WelcomeLetterController@showDatatable')->name('webadmin.welcomeletters.reorder');
    Route::post('welcomeletters/reorder','Admin\WelcomeLetterController@updateOrder');
    
    //Demo 
    Route::get('/demo', 'Admin\DemoController@index')->name('webadmin.demo');
    Route::any('/demo-session-details', 'Admin\DemoController@demo_session_details')->name('webadmin.demoSessionDetails');

    //Demo Details
    Route::get('/demo-details', 'Admin\DemoDetailsController@index')->name('webadmin.demoDetails');
    Route::get('/demo-details-add', 'Admin\DemoDetailsController@add')->name('webadmin.demoDetailsAdd');
    Route::post('/demo-details-save', 'Admin\DemoDetailsController@save')->name('webadmin.demoDetailsSave');
    Route::get('/demo-details-edit/{id}', 'Admin\DemoDetailsController@edit')->name('webadmin.demoDetailsEdit');
    Route::post('/demo-details-update/{id}', 'Admin\DemoDetailsController@update')->name('webadmin.demoDetailsUpdate');
    Route::get('/demo-details-delete/{id}', 'Admin\DemoDetailsController@delete')->name('webadmin.demoDetailsDelete');
    
    //Demo Sessions
    Route::get('/demo-sessions', 'Admin\DemoSessionsController@index')->name('webadmin.demoSessions');
    Route::get('/demo-sessions-add', 'Admin\DemoSessionsController@add')->name('webadmin.demoSessionsAdd');
    Route::post('/demo-sessions-save', 'Admin\DemoSessionsController@save')->name('webadmin.demoSessionsSave');
    Route::get('/demo-sessions-edit/{id}', 'Admin\DemoSessionsController@edit')->name('webadmin.demoSessionsEdit');
    Route::post('/demo-sessions-update/{id}', 'Admin\DemoSessionsController@update')->name('webadmin.demoSessionsUpdate');
    Route::get('/demo-sessions-delete/{id}', 'Admin\DemoSessionsController@delete')->name('webadmin.demoSessionsDelete');
    
    
    // Sales Presenter Cover
    Route::get('/salespresentercover', 'Admin\SalespresenterCoverController@index')->name('webadmin.salespresentercover');
    Route::get('/salespresentercover-add', 'Admin\SalespresenterCoverController@add')->name('webadmin.salespresentercoverAdd');
    Route::post('/salespresentercover-save', 'Admin\SalespresenterCoverController@save')->name('webadmin.salespresentercoverSave');
    Route::get('/salespresentercover-edit/{id}', 'Admin\SalespresenterCoverController@edit')->name('webadmin.salespresentercoverEdit');
    Route::post('/salespresentercover-update/{id}', 'Admin\SalespresenterCoverController@update')->name('webadmin.salespresentercoverUpdate');
    Route::get('/salespresentercover-delete/{id}', 'Admin\SalespresenterCoverController@delete')->name('webadmin.salespresentercoverDelete');

    Route::get('salespresentercover/reorder','Admin\SalespresenterCoverController@showDatatable')->name('webadmin.salespresentercover.reorder');
    Route::post('salespresentercover/reorder','Admin\SalespresenterCoverController@updateOrder');
    
    
    Route::get('mf-rating/cron-list', 'Admin\MFRattingController@cron_list')->name('webadmin.mf_rating_cron_list');
    Route::get('mf-rating/cron', 'Admin\MFRattingController@cron')->name('webadmin.mf_rating_cron');
    Route::get('mf-rating/index', 'Admin\MFRattingController@index')->name('webadmin.mf_rating_index');
    Route::post('mf-rating/download-csv', 'Admin\MFRattingController@downloadCSV')->name('webadmin.mfRatingDownloadCSV');
    Route::get('mf-rating/equity', 'Admin\MFRattingController@equity')->name('webadmin.mf_rating_equity');
    Route::post('mf-rating/update_equity', 'Admin\MFRattingController@update_equity')->name('webadmin.mf_rating_update_equity');
    Route::get('mf-rating/debt', 'Admin\MFRattingController@debt')->name('webadmin.mf_rating_debt');
    Route::post('mf-rating/update_debt', 'Admin\MFRattingController@update_debt')->name('webadmin.mf_rating_update_debt');
    Route::get('mf-rating/hybrid', 'Admin\MFRattingController@hybrid')->name('webadmin.mf_rating_hybrid');
    Route::post('mf-rating/update_hybrid', 'Admin\MFRattingController@update_hybrid')->name('webadmin.mf_rating_update_hybrid');

    Route::get('mf-rating/point', 'Admin\MFRattingController@point')->name('webadmin.mf_rating_point');
    Route::get('mf-rating/point-export', 'Admin\MFRattingController@export')->name('webadmin.mf_rating_point_export');
    Route::get('mf-rating/point-edit', 'Admin\MFRattingController@point_edit')->name('webadmin.mf_rating_point_edit');
    Route::post('mf-rating/point-update', 'Admin\MFRattingController@point_update')->name('webadmin.mf_rating_point_update');

    Route::get('mf-rating/score', 'Admin\MFRattingController@score')->name('webadmin.mf_rating_score');
    Route::get('mf-rating/score-export', 'Admin\MFRattingController@score_export')->name('webadmin.mf_rating_score_export');
    Route::get('mf-rating/score-edit', 'Admin\MFRattingController@score_edit')->name('webadmin.mf_rating_score_edit');
    Route::post('mf-rating/score-update', 'Admin\MFRattingController@score_update')->name('webadmin.mf_rating_score_update');

    Route::get('mf-rating/category-cron', 'Admin\MFRattingController@category_cron')->name('webadmin.mf_rating_category_cron');
    Route::get('mf-rating/scheme-cron', 'Admin\MFRattingController@scheme_cron')->name('webadmin.mf_rating_scheme_cron');

    Route::get('mf-rating/point-history', 'Admin\MFRattingController@point_history')->name('webadmin.mf_rating_point_history');
    Route::get('mf-rating/point-history-date', 'Admin\MFRattingController@point_history_date')->name('webadmin.mf_rating_point_history_date');
    Route::get('mf-rating/point-history-delete', 'Admin\MFRattingController@point_history_delete')->name('webadmin.mf_rating_point_history_delete');
    
    Route::get('mf-rating/score-history', 'Admin\MFRattingController@score_history')->name('webadmin.mf_rating_score_history');
    Route::get('mf-rating/score-history-date', 'Admin\MFRattingController@score_history_date')->name('webadmin.mf_rating_score_history_date');
    Route::get('mf-rating/score-history-delete', 'Admin\MFRattingController@score_history_delete')->name('webadmin.mf_rating_score_history_delete');

    Route::get('mf-rating/rating', 'Admin\MFRattingController@rating')->name('webadmin.mf_rating_rating');
    Route::get('mf-rating/rating-export', 'Admin\MFRattingController@rating_export')->name('webadmin.mf_rating_rating_export');

    Route::get('mf-rating/point-cron/{type}', 'Admin\MFRattingController@point_cron')->name('webadmin.mf_rating_point_cron');
    Route::get('mf-rating/score-cron', 'Admin\MFRattingController@score_cron')->name('webadmin.mf_rating_score_cron');
    Route::get('mf-rating/rating-cron', 'Admin\MFRattingController@rating_cron')->name('webadmin.mf_rating_rating_cron');
    Route::get('mf-rating/point-history-cron', 'Admin\MFRattingController@point_history_cron')->name('webadmin.mf_rating_point_history_cron');
    Route::get('mf-rating/score-history-cron', 'Admin\MFRattingController@score_history_cron')->name('webadmin.mf_rating_score_history_cron');
    
    Route::get('/users/multi', 'Admin\UserController@multiuser')->name('webadmin.users.multi');
    Route::get('/users/multiuserdetail/{id}', 'Admin\UserController@multiuserdetail')->name('webadmin.users.multiuserdetail');
    Route::get('/users/multi-edit/{id}', 'Admin\UserController@edit_multi')->name('webadmin.users.multiEdit');
    Route::get('/users/export-multi-user/{id}', 'Admin\UserController@exportCSVMultiUser')->name('webadmin.users.exportCSVMultiUser');
    Route::get('/users/multi-export', 'Admin\UserController@multiUserExport')->name('webadmin.users.multiUserExport');
    Route::post('/users/multi-export-download', 'Admin\UserController@multiUserExportDownload')->name('webadmin.users.multiUserExportdownload');
    Route::post('/users/multi-update/{id}', 'Admin\UserController@update_multi')->name('webadmin.users.multiUpdate');
    
    
    Route::get('/site-setting/index', 'Admin\SiteSettingController@index')->name('webadmin.site_setting');
    Route::get('/site-setting-edit/{id}', 'Admin\SiteSettingController@edit')->name('webadmin.site_settingEdit');
    Route::post('/site-setting-update/{id}', 'Admin\SiteSettingController@update')->name('webadmin.site_settingUpdate');

    Route::get('/report/membership', 'Admin\ReportController@membership')->name('webadmin.report_membership');
    Route::get('/report/store', 'Admin\ReportController@store')->name('webadmin.report_store');
    Route::get('/report/download', 'Admin\ReportController@download')->name('webadmin.report_download');
    Route::post('/report/action', 'Admin\ReportController@actions')->name('webadmin.report_download_action');

    Route::get('/mf-research/cron', 'Admin\MfresearchCronController@index')->name('webadmin.mf-research-cron');
    Route::get('/mf-research/cron-detail/{id}', 'Admin\MfresearchCronController@detail')->name('webadmin.mf-research-cron-detail');

    Route::post('/mf-research/cron-detail-table', 'Admin\MfresearchCronController@detailTable')->name('webadmin.mf-research-cron-detail-table');

    Route::get('/mf-research/update-cron/{id}', 'Admin\MfresearchCronController@update')->name('webadmin.mf-research-cron-update');
    Route::get('/mf-research/cron-list', 'Admin\MfresearchCronController@list')->name('webadmin.mf-research-cron-list');
    Route::post('/mf-research/cron-update-discription', 'Admin\MfresearchCronController@update_description')->name('webadmin.mf-research-cron-update-discription');
    Route::get('/mf-research/cron-history', 'Admin\MfresearchCronController@cron_history')->name('webadmin.mf-research-cron-history');
    Route::get('/mf-research/cron-start', 'Admin\MfresearchCronController@start')->name('webadmin.mf-research-cron-start');
    
    // 27-12-2023 static cron list
    Route::get('/mf-research/cron-list-static', 'Admin\MfresearchCronController@listStaticCron')->name('webadmin.mf-research-static-cron-list');
    // 27-12-2023 static cron list

    // 22-12-2023 Manual cron Start
    Route::get('/mf-research/factsheet-cron-start', 'Admin\MfresearchCronController@allStaticCronStart')->name('webadmin.mf-research-factsheet-cron-start');
    // 22-12-2023 Manual cron End

    
    Route::get('/mso-model-portfolio/lumpsum', 'Admin\MsoModelPortfolioController@lumpsum')->name('webadmin.msomodelportfolio_lumpsum');
    Route::get('/mso-model-portfolio/sip', 'Admin\MsoModelPortfolioController@sip')->name('webadmin.msomodelportfolio_sip');
    Route::get('/mso-model-portfolio/stp', 'Admin\MsoModelPortfolioController@stp')->name('webadmin.msomodelportfolio_stp');
    Route::get('/mso-model-portfolio/swp', 'Admin\MsoModelPortfolioController@swp')->name('webadmin.msomodelportfolio_swp');

    Route::get('/mso-model-portfolio/lumpsum-delete', 'Admin\MsoModelPortfolioController@lumpsum_delete')->name('webadmin.msomodelportfolio_lumpsum_delete');
    Route::get('/mso-model-portfolio/sip-delete', 'Admin\MsoModelPortfolioController@sip_delete')->name('webadmin.msomodelportfolio_sip_delete');
    Route::get('/mso-model-portfolio/stp-delete', 'Admin\MsoModelPortfolioController@stp_delete')->name('webadmin.msomodelportfolio_stp_delete');
    Route::get('/mso-model-portfolio/swp-delete', 'Admin\MsoModelPortfolioController@swp_delete')->name('webadmin.msomodelportfolio_swp_delete');

    Route::get('/mso-model-portfolio/delete','Admin\MsoModelPortfolioController@msoDelete')->name('webadmin.msomodelportfolioDelete');
    Route::get('/mso-model-portfolio/msomodelportfoliouploadcsv', 'Admin\MsoModelPortfolioController@uploadcsv')->name('webadmin.msomodelportfoliouploadcsv');
    Route::post('/mso-model-portfolio/readcsv','Admin\MsoModelPortfolioController@ReadCsv')->name('webadmin.msomodelreadcsv');

    Route::post('/mso-model-portfolio/addmsotype','Admin\MsoModelPortfolioController@AddMsoType')->name('webadmin.addmsotype');
    Route::get('/mso-model-portfolio/deletemsodata','Admin\MsoModelPortfolioController@DeleteMsoData')->name('webadmin.deletemsodata');


    Route::get('/mso-model-portfolio/addmsoinvestmentmode','Admin\MsoModelPortfolioController@investment_mode')->name('webadmin.addmsoinvestmentmode');
    Route::get('/mso-model-portfolio/addmsoassetclass','Admin\MsoModelPortfolioController@asset_class')->name('webadmin.addmsoassetclass');
    Route::get('/mso-model-portfolio/addmsotimehorizon','Admin\MsoModelPortfolioController@time_horizon')->name('webadmin.addmsotimehorizon');
    Route::get('/mso-model-portfolio/addmsointerestrate','Admin\MsoModelPortfolioController@interest_rate')->name('webadmin.addmsointerestrate');
    Route::get('/mso-model-portfolio/addmsoequitymark','Admin\MsoModelPortfolioController@equity_mark')->name('webadmin.addmsoequitymark');
    Route::get('/mso-model-portfolio/addmsoriskprofile','Admin\MsoModelPortfolioController@risk_profile')->name('webadmin.addmsoriskprofile');
    
    


    Route::get('/trigger/index', 'Admin\TriggerController@index')->name('webadmin.trigger_index');
    Route::get('/trigger/list', 'Admin\TriggerController@list')->name('webadmin.trigger_list');
    Route::get('/trigger/edit/{id}', 'Admin\TriggerController@edit')->name('webadmin.trigger_edit');
    Route::post('/trigger/update/{id}', 'Admin\TriggerController@update')->name('webadmin.trigger_update');
    Route::get('/trigger/setup', 'Admin\TriggerController@setup')->name('webadmin.trigger_setup');
    Route::post('/trigger/setup-update', 'Admin\TriggerController@setup_update')->name('webadmin.trigger_setup_update');

    Route::get('/trigger/default', 'Admin\TriggerController@default')->name('webadmin.trigger_default');
    Route::get('/trigger/default-add', 'Admin\TriggerController@default_add')->name('webadmin.trigger_default_add');
    Route::post('/trigger/default-save', 'Admin\TriggerController@default_save')->name('webadmin.trigger_default_save');
    Route::get('/trigger/default-edit/{id}', 'Admin\TriggerController@default_edit')->name('webadmin.trigger_default_edit');
    Route::post('/trigger/default-update', 'Admin\TriggerController@default_update')->name('webadmin.trigger_default_update');
    Route::get('/trigger/default-delete/{id}', 'Admin\TriggerController@default_delete')->name('webadmin.trigger_default_delete');
    Route::get('/trigger/default-reorder', 'Admin\TriggerController@default_reorder')->name('webadmin.trigger_default_reorder');
    
    
    Route::get('/admin-user', 'Admin\AdminUserController@index')->name('webadmin.admin_userIndex');
    Route::get('/admin-user-add', 'Admin\AdminUserController@add')->name('webadmin.admin_userAdd');
    Route::post('/admin-user-save', 'Admin\AdminUserController@save')->name('webadmin.admin_userSave');
    Route::get('/admin-user-edit/{id}', 'Admin\AdminUserController@edit')->name('webadmin.admin_userEdit');
    Route::post('/admin-user-update/{id}', 'Admin\AdminUserController@update')->name('webadmin.admin_userUpdate');
    Route::get('/admin-user-delete/{id}', 'Admin\AdminUserController@delete')->name('webadmin.admin_userDelete');
    
    Route::get('/admin-user-role', 'Admin\AdminUserController@user_role')->name('webadmin.admin_user_roleIndex');
    Route::get('/admin-user-add-role', 'Admin\AdminUserController@user_role_add')->name('webadmin.admin_user_roleAdd');
    Route::post('/admin-user-role-save', 'Admin\AdminUserController@user_role_save')->name('webadmin.admin_user_roleSave');
    Route::get('/admin-user-role-edit/{id}', 'Admin\AdminUserController@user_role_edit')->name('webadmin.admin_user_roleEdit');
    Route::post('/admin-user-role-update/{id}', 'Admin\AdminUserController@user_role_update')->name('webadmin.admin_user_roleUpdate');
    Route::get('/admin-user-role-delete/{id}', 'Admin\AdminUserController@user_role_delete')->name('webadmin.admin_user_roleDelete');
    
    // Asif 23 April 2023
    Route::get('notification-setting', 'Admin\NotificationController@setting')->name('webadmin.notification_setting');
    Route::post('notification-setting', 'Admin\NotificationController@setting')->name('webadmin.notification_setting');
    
    Route::get('/notification/group-create', 'Admin\NotificationGroup@index')->name('webadmin.notificationGroupCreate');
    Route::post('/notification/group-save', 'Admin\NotificationGroup@saveGroup')->name('webadmin.notificationsaveGroup');
    Route::get('/notification/group-index', 'Admin\NotificationGroup@groupIndex')->name('webadmin.notificationgroupIndex');
    Route::get('/notification/group-user/{id}', 'Admin\NotificationGroup@groupUserindex')->name('webadmin.notificationgroupUserindex');
    Route::get('/notification/group-remove', 'Admin\NotificationGroup@removeGroup')->name('webadmin.notificationRemoveGroup');
    Route::post('/notification/group-user-remove', 'Admin\NotificationGroup@removeGroupUser')->name('webadmin.notificationremoveGroupUser');
    Route::get('/notification/test-send', 'Admin\NotificationGroup@sendFile')->name('webadmin.notificationsendFile');
    Route::get('/notification/send-image-from', 'Admin\NotificationGroup@sendForm')->name('webadmin.notificationsendForm');
    Route::get('/notification/getNotificationGroups', 'Admin\NotificationGroup@getNotificationGroups')->name('webadmin.getNotificationGroups');
    Route::post('/notification/send-to-group', 'Admin\NotificationGroup@saveToGroupMember')->name('webadmin.notificationsaveToGroupMember');
    Route::get('/notification/getNotificationUsertype', 'Admin\NotificationGroup@getNotificationUsertype')->name('webadmin.getNotificationUsertype');
    
    Route::get('/home/whatsnew-reorder','Admin\HomeController@showDatatable')->name('webadmin.whatsnew.reorder');
    Route::post('/home/whatsnew-reorder', 'Admin\HomeController@updateOrder');

    Route::get('/become-a-member', 'Admin\BecomeAMemberController@index')->name('webadmin.become_a_member');
    Route::get('/become-a-member-add', 'Admin\BecomeAMemberController@add')->name('webadmin.become_a_member_add');
    Route::post('/become-a-member-save', 'Admin\BecomeAMemberController@save')->name('webadmin.become_a_member_save');
    Route::get('/become-a-member-edit/{id}', 'Admin\BecomeAMemberController@edit')->name('webadmin.become_a_member_edit');
    Route::post('/become-a-member-update', 'Admin\BecomeAMemberController@update')->name('webadmin.become_a_member_update');
    Route::get('/become-a-member-delete/{id}', 'Admin\BecomeAMemberController@delete')->name('webadmin.become_a_member_delete');
    Route::get('/become_a_member_reorder', 'Admin\BecomeAMemberController@reorder')->name('webadmin.become_a_member_reorder');
    Route::post('become_a_member_reorder_update','Admin\BecomeAMemberController@updateOrder');

    Route::get('/order/status', 'Admin\OrderController@status_index')->name('webadmin.orderStatus');
    Route::get('/order/status-add', 'Admin\OrderController@status_add')->name('webadmin.orderStatusAdd');
    Route::post('/order/status-save', 'Admin\OrderController@status_save')->name('webadmin.orderStatusSave');
    Route::get('/order/status-edit/{id}', 'Admin\OrderController@status_edit')->name('webadmin.orderStatusEdit');
    Route::post('/order/status-update/{id}', 'Admin\OrderController@status_update')->name('webadmin.orderStatusUpdate');
    Route::get('/order/status-delete/{id}', 'Admin\OrderController@status_delete')->name('webadmin.orderStatusDelete');

    Route::get('/reward-point/dashboard', 'Admin\RewardPointController@dashboard')->name('webadmin.reward_point.dashboard');
    Route::get('/reward-point/total-points-not-claimed', 'Admin\RewardPointController@total_points_not_claimed')->name('webadmin.reward_point.total_points_not_claimed');
    Route::get('/reward-point/admin-user', 'Admin\RewardPointController@admin_user')->name('webadmin.reward_point.admin_user');
    Route::get('/reward-point/claim-point', 'Admin\RewardPointController@claim_point')->name('webadmin.reward_point.claim_point');

    Route::get('/admin-user-monthly-points', 'Admin\AdminUserController@monthly_points')->name('webadmin.admin_user_monthly_points');

    Route::get('/membership-referral/list', 'Admin\MembershipReferralController@list')->name('webadmin.membership_referral.list');
    Route::get('/membership-referral/setting', 'Admin\MembershipReferralController@setting')->name('webadmin.membership_referral.setting');
    Route::get('/membership-referral/setting-edit/{id}', 'Admin\MembershipReferralController@settingedit')->name('webadmin.membership_referral.edit_setting');
    Route::post('/membership-referral/setting-update', 'Admin\MembershipReferralController@settingupdate')->name('webadmin.membership_referral.setting_update');
    

    Route::get('/analytics/dashboard', 'Admin\AnalyticsController@dashboard')->name('webadmin.analytics.dashboard');

    Route::get('/analytics/calculator', 'Admin\AnalyticsController@calculator')->name('webadmin.analytics.calculator');
    Route::get('/analytics/calculators', 'Admin\AnalyticsController@calculators')->name('webadmin.analytics.calculators');
    Route::get('/analytics/calculator_scheme_wise', 'Admin\AnalyticsController@calculator_scheme_wise')->name('webadmin.analytics.calculator_scheme_wise');
    Route::get('/analytics/calculator_suggested_schemes', 'Admin\AnalyticsController@calculator_suggested_schemes')->name('webadmin.analytics.calculator_suggested_schemes');
    Route::get('/analytics/calculator_user_wise', 'Admin\AnalyticsController@calculator_user_wise')->name('webadmin.analytics.calculator_user_wise');

    Route::get('/analytics/mf-research', 'Admin\AnalyticsController@mf_research')->name('webadmin.analytics.mf_research');
    Route::get('/analytics/mf-research-detail', 'Admin\AnalyticsController@mf_research_detail')->name('webadmin.analytics.mf_research_detail');
    Route::get('/analytics/mf-research-user-wise', 'Admin\AnalyticsController@mf_research_user_wise')->name('webadmin.analytics.mf_research_user_wise');

    Route::get('/analytics/membership_referral_dashboard', 'Admin\AnalyticsMembershipReferralController@membership_referral_dashboard')->name('webadmin.analytics.membership_referral_dashboard');
    Route::get('/analytics/membership_referral_list', 'Admin\AnalyticsMembershipReferralController@membership_referral_list')->name('webadmin.analytics.membership_referral_list');
    
    Route::get('/analytics/membership_points_dashboard', 'Admin\AnalyticsMembershipReferralController@membership_points_dashboard')->name('webadmin.analytics.membership_points_dashboard');
    Route::get('/analytics/membership_points_list', 'Admin\AnalyticsMembershipReferralController@membership_points_list')->name('webadmin.analytics.membership_points_list');
    
    Route::get('/analytics/coupon_code_dashboard', 'Admin\AnalyticsMembershipReferralController@coupon_code_dashboard')->name('webadmin.analytics.coupon_code_dashboard');
    Route::get('/analytics/coupon_code_list', 'Admin\AnalyticsMembershipReferralController@coupon_code_list')->name('webadmin.analytics.coupon_code_list');
    Route::get('/analytics/coupon_code_claimed', 'Admin\AnalyticsMembershipReferralController@coupon_code_claimed')->name('webadmin.analytics.coupon_code_claimed');

    Route::get('/analytics/fp_custom_list', 'Admin\AnalyticsFundPerformanceController@custom_list')->name('webadmin.analytics.fp_custom_list');
    Route::get('/analytics/fp_custom_scheme_wise', 'Admin\AnalyticsFundPerformanceController@custom_scheme_wise')->name('webadmin.analytics.fp_custom_scheme_wise');
    Route::get('/analytics/fp_category_wise', 'Admin\AnalyticsFundPerformanceController@category_wise')->name('webadmin.analytics.fp_category_wise');

    Route::get('/analytics/user-module', 'Admin\AnalyticsController@user_module')->name('webadmin.analytics.user_module');
    Route::get('/analytics/user-profile', 'Admin\AnalyticsController@user_profile')->name('webadmin.analytics.user_profile');
    Route::get('/analytics/library', 'Admin\AnalyticsController@library')->name('webadmin.analytics.library');
    Route::get('/analytics/store', 'Admin\AnalyticsController@store')->name('webadmin.analytics.store');
    Route::get('/analytics/membership', 'Admin\AnalyticsController@membership')->name('webadmin.analytics.membership');

    Route::get('/analytics/banner/name_of_member', 'Admin\AnalyticsMarketingBannersController@name_of_member')->name('webadmin.analytics.banner_name_of_member');
    Route::get('/analytics/banner/download_by_category', 'Admin\AnalyticsMarketingBannersController@download_by_category')->name('webadmin.analytics.banner_download_by_category');
    Route::get('/analytics/banner/most_download', 'Admin\AnalyticsMarketingBannersController@most_download')->name('webadmin.analytics.banner_most_download');
    Route::get('/analytics/banner/least_download', 'Admin\AnalyticsMarketingBannersController@least_download')->name('webadmin.analytics.banner_least_download');
    Route::get('/analytics/banner/top_download', 'Admin\AnalyticsMarketingBannersController@top_download')->name('webadmin.analytics.banner_top_download');
    Route::get('/analytics/banner/name_wise_download', 'Admin\AnalyticsMarketingBannersController@name_wise_download')->name('webadmin.analytics.banner_name_wise_download');

    Route::get('/analytics/video/name_of_member', 'Admin\AnalyticsMarketingVideosController@name_of_member')->name('webadmin.analytics.video_name_of_member');
    Route::get('/analytics/video/download_by_category', 'Admin\AnalyticsMarketingVideosController@download_by_category')->name('webadmin.analytics.video_download_by_category');
    Route::get('/analytics/video/most_download', 'Admin\AnalyticsMarketingVideosController@most_download')->name('webadmin.analytics.video_most_download');
    Route::get('/analytics/video/least_download', 'Admin\AnalyticsMarketingVideosController@least_download')->name('webadmin.analytics.video_least_download');
    Route::get('/analytics/video/top_download', 'Admin\AnalyticsMarketingVideosController@top_download')->name('webadmin.analytics.video_top_download');
    Route::get('/analytics/video/name_wise_download', 'Admin\AnalyticsMarketingVideosController@name_wise_download')->name('webadmin.analytics.video_name_wise_download');

    Route::get('/analytics/sales_presenter/name_of_member', 'Admin\AnalyticsSalesPresenterController@name_of_member')->name('webadmin.analytics.sales_presenter_name_of_member');
    Route::get('/analytics/sales_presenter/download_by_category', 'Admin\AnalyticsSalesPresenterController@download_by_category')->name('webadmin.analytics.sales_presenter_download_by_category');
    Route::get('/analytics/sales_presenter/most_download', 'Admin\AnalyticsSalesPresenterController@most_download')->name('webadmin.analytics.sales_presenter_most_download');
    Route::get('/analytics/sales_presenter/least_download', 'Admin\AnalyticsSalesPresenterController@least_download')->name('webadmin.analytics.sales_presenter_least_download');
    Route::get('/analytics/sales_presenter/top_download', 'Admin\AnalyticsSalesPresenterController@top_download')->name('webadmin.analytics.sales_presenter_top_download');
    Route::get('/analytics/sales_presenter/name_wise_download', 'Admin\AnalyticsSalesPresenterController@name_wise_download')->name('webadmin.analytics.sales_presenter_name_wise_download');

    Route::get('/analytics/pre_made/name_of_member', 'Admin\AnalyticsPreMadeController@name_of_member')->name('webadmin.analytics.pre_made_name_of_member');
    Route::get('/analytics/pre_made/download_by_category', 'Admin\AnalyticsPreMadeController@download_by_category')->name('webadmin.analytics.pre_made_download_by_category');
    Route::get('/analytics/pre_made/most_download', 'Admin\AnalyticsPreMadeController@most_download')->name('webadmin.analytics.pre_made_most_download');
    Route::get('/analytics/pre_made/least_download', 'Admin\AnalyticsPreMadeController@least_download')->name('webadmin.analytics.pre_made_least_download');
    Route::get('/analytics/pre_made/top_download', 'Admin\AnalyticsPreMadeController@top_download')->name('webadmin.analytics.pre_made_top_download');
    Route::get('/analytics/pre_made/name_wise_download', 'Admin\AnalyticsPreMadeController@name_wise_download')->name('webadmin.analytics.pre_made_name_wise_download');

    Route::get('/analytics/readymade/name_of_member', 'Admin\AnalyticsReadymadeController@name_of_member')->name('webadmin.analytics.readymade_name_of_member');
    Route::get('/analytics/readymade/download_by_category', 'Admin\AnalyticsReadymadeController@download_by_category')->name('webadmin.analytics.readymade_download_by_category');
    Route::get('/analytics/readymade/most_download', 'Admin\AnalyticsReadymadeController@most_download')->name('webadmin.analytics.readymade_most_download');
    Route::get('/analytics/readymade/least_download', 'Admin\AnalyticsReadymadeController@least_download')->name('webadmin.analytics.readymade_least_download');
    Route::get('/analytics/readymade/top_download', 'Admin\AnalyticsReadymadeController@top_download')->name('webadmin.analytics.readymade_top_download');
    Route::get('/analytics/readymade/name_wise_download', 'Admin\AnalyticsReadymadeController@name_wise_download')->name('webadmin.analytics.readymade_name_wise_download');

    Route::get('/analytics/welcome/name_of_member', 'Admin\AnalyticsWelcomeController@name_of_member')->name('webadmin.analytics.welcome_name_of_member');
    Route::get('/analytics/welcome/most_download', 'Admin\AnalyticsWelcomeController@most_download')->name('webadmin.analytics.welcome_most_download');
    Route::get('/analytics/welcome/least_download', 'Admin\AnalyticsWelcomeController@least_download')->name('webadmin.analytics.welcome_least_download');
    Route::get('/analytics/welcome/number_of_customised_template', 'Admin\AnalyticsWelcomeController@number_of_customised_template')->name('webadmin.analytics.welcome_number_of_customised_template');
    Route::get('/analytics/welcome/name_wise_download', 'Admin\AnalyticsWelcomeController@name_wise_download')->name('webadmin.analytics.welcome_name_wise_download');

    Route::get('/analytics/isp/suggested_asset_allocation', 'Admin\AnalyticsSuitabilityProfilerController@suggested_asset_allocation')->name('webadmin.analytics.sp_suggested_asset_allocation');
    Route::get('/analytics/isp/suggested_product', 'Admin\AnalyticsSuitabilityProfilerController@suggested_product')->name('webadmin.analytics.sp_suggested_product');
    Route::get('/analytics/isp/dashboard', 'Admin\AnalyticsSuitabilityProfilerController@dashboard')->name('webadmin.analytics.sp_dashboard');
    Route::get('/analytics/isp/no_of_download_user', 'Admin\AnalyticsSuitabilityProfilerController@no_of_download_user')->name('webadmin.analytics.sp_no_of_download_user');
    Route::get('/analytics/isp/no_of_saved_user', 'Admin\AnalyticsSuitabilityProfilerController@no_of_saved_user')->name('webadmin.analytics.sp_no_of_saved_user');

    Route::get('/analytics/order/dashboard', 'Admin\AnalyticsOrderController@dashboard')->name('webadmin.analytics.order_dashboard');
    Route::get('/analytics/order/added_to_cart', 'Admin\AnalyticsOrderController@added_to_cart')->name('webadmin.analytics.order_added_to_cart');
    Route::get('/analytics/order/least_product', 'Admin\AnalyticsOrderController@least_product')->name('webadmin.analytics.order_least_product');
    Route::get('/analytics/order/video', 'Admin\AnalyticsOrderController@video')->name('webadmin.analytics.order_video');
    Route::get('/analytics/order/no_checkout', 'Admin\AnalyticsOrderController@no_checkout')->name('webadmin.analytics.order_no_checkout');
    Route::get('/analytics/order/no_added_to_cart', 'Admin\AnalyticsOrderController@no_added_to_cart')->name('webadmin.analytics.order_no_added_to_cart');
    Route::get('/analytics/order/no_purchase', 'Admin\AnalyticsOrderController@no_purchase')->name('webadmin.analytics.order_no_purchase');
    Route::get('/analytics/order/revenue_generated', 'Admin\AnalyticsOrderController@revenue_generated')->name('webadmin.analytics.order_revenue_generated');
    Route::get('/analytics/order/point_used', 'Admin\AnalyticsOrderController@point_used')->name('webadmin.analytics.order_point_used');
    Route::get('/analytics/order/coupon_used', 'Admin\AnalyticsOrderController@coupon_used')->name('webadmin.analytics.order_coupon_used');
    Route::get('/analytics/order/abandoned_checkouts', 'Admin\AnalyticsOrderController@abandoned_checkouts')->name('webadmin.analytics.order_abandoned_checkouts');

    Route::get('/analytics/library/dashdord', 'Admin\AnalyticsLibraryController@dashdord')->name('webadmin.analytics.library_dashdord');
    Route::get('/analytics/library/name_wise_download', 'Admin\AnalyticsLibraryController@name_wise_download')->name('webadmin.analytics.library_name_wise_download');

    Route::get('/analytics/membership_new', 'Admin\AnalyticsMembershipController@membership_new')->name('webadmin.analytics.membership_new');
    Route::get('/analytics/membership_renewal', 'Admin\AnalyticsMembershipController@membership_renewal')->name('webadmin.analytics.membership_renewal');
    Route::get('/analytics/membership_add_on', 'Admin\AnalyticsMembershipController@membership_add_on')->name('webadmin.analytics.membership_add_on');
    Route::get('/analytics/membership_upgrade', 'Admin\AnalyticsMembershipController@membership_upgrade')->name('webadmin.analytics.membership_upgrade');

    Route::get('/analytics/cc_dashboard', 'Admin\AnalyticsClientCommunicationController@dashboard')->name('webadmin.analytics.cc_dashboard');
    Route::get('/analytics/cc_client_comm_title', 'Admin\AnalyticsClientCommunicationController@client_comm_title')->name('webadmin.analytics.cc_client_comm_title');
    Route::get('/analytics/cc_category_wise', 'Admin\AnalyticsClientCommunicationController@category_wise')->name('webadmin.analytics.cc_category_wise');
    Route::get('/analytics/cc_most_used', 'Admin\AnalyticsClientCommunicationController@most_used')->name('webadmin.analytics.cc_most_used');

    Route::get('/analytics/other_download', 'Admin\AnalyticsController@other_download')->name('webadmin.analytics.other_download');

    Route::get('/analytics/trail_calculator', 'Admin\AnalyticsController@trail_calculator')->name('webadmin.analytics.trail_calculator');



    

    Route::get('/membership-module/trial_taken', 'Admin\MembershipModuleController@trial_taken')->name('webadmin.membership_module.trial_taken');
    Route::get('/membership-module/upgrade_to_premium', 'Admin\MembershipModuleController@upgrade_to_premium')->name('webadmin.membership_module.upgrade_to_premium');
    Route::get('/membership-module/downgrade_to_basic', 'Admin\MembershipModuleController@downgrade_to_basic')->name('webadmin.membership_module.downgrade_to_basic');
    Route::get('/membership-module/discontinued', 'Admin\MembershipModuleController@discontinued')->name('webadmin.membership_module.discontinued');
    Route::get('/membership-module/new_sub_user', 'Admin\MembershipModuleController@new_sub_user')->name('webadmin.membership_module.new_sub_user');


    
    
});


