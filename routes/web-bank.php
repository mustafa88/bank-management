<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Bank\TitleOneController;
use App\Http\Controllers\Bank\TitleTwoController;
use App\Http\Controllers\bank\EnterpriseController;
use App\Http\Controllers\bank\ProjectsController;
use App\Http\Controllers\bank\CityController;
use App\Http\Controllers\bank\ProjectsCityController;
use App\Http\Controllers\bank\IncomeController;
use App\Http\Controllers\bank\ExpenseController;
use App\Http\Controllers\bank\BanksController;
use App\Http\Controllers\bank\BankslineController;
use App\Http\Controllers\bank\BanksdetailController;
use App\Http\Controllers\bank\ReportbankController;
use App\Http\Controllers\Bank\CampaignsController;
use App\Http\Controllers\Bank\ReportProjectController;
use App\Http\Controllers\Bank\DonateworthController;
use App\Http\Controllers\Bank\DonateTypeController;
use App\Http\Controllers\Usb\UsbIncomeController;
use App\Http\Controllers\Usb\UsbExpenseController;
use App\Http\Controllers\Bank\ExportImportController;
use App\Http\Controllers\Usb\AdahiController;
use App\Http\Controllers\Bank\DashboardController;
use App\Http\Controllers\System\PermissionController;
use App\Models\Program\Pmenu;
    /*middleware
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web"  group. Now create something great!
    |
    */

/*try {
    \DB::beginTransaction();
    ///...
    \DB::commit();
}catch(\Exception $exp) {
    \DB::rollBack();
    $resultArr['status'] = false;
    $resultArr['cls'] = 'error';
    $resultArr['msg'] = 'حصل خطا اثناء الحفظ';
    $resultArr['errormsg'] = $exp->getMessage();
    return $resultArr;
}*/
/*
 * php artisan make:model Program/Permissionuser
 * php artisan make:controller Bank/DashboardController --resource
 * php artisan make:request Usb/UsbExpenseRequest
 *
 * php artisan make:controller Usb/ExportImportController
 */

/**
 * https://css-tricks.com/simple-css-row-column-highlighting/
 */

/**
Route::get('/', function () {
    return view('welcome');
});
**/

/**
Route::group(['prefix' => 'inventory/color', 'namespace' => 'Inventory', 'middleware' => ['web']], function () {
Route::get('show', 'ColorController@getColors')->name('color.show');
Route::post('store.ajax', 'ColorController@storeAjax')->name('color.store.ajax');
Route::put('update.ajax', 'ColorController@updateAjax')->name('color.update.ajax');
Route::delete('delete.ajax', 'ColorController@deleteAjax')->name('color.delete.ajax');
});

 */

//::middleware(['auth', 'role:user'])

Route::get('/', function () {
    //xdebug_info();
    return view('demoangle');
})->middleware(['auth'])->name('home');





Route::group(['prefix' => 'system', 'namespace' => 'System', 'middleware' => ['auth','permprog:permuser.show']], function () {
    //xdebug_info();,
   //$menu =  Pmenu::with('submenu','program','submenu.program')->whereNull('id_menu_sub')->get();
   //return $menu;
    Route::get('permissionUser', [PermissionController::class ,'permissionUser'])->name('permuser.show');
    //הצגץ הרשאוץ למשתמש
    Route::get('permissionUserShow/{idUser?}', [PermissionController::class ,'permissionUserShow'])->name('permuser.showajax');
    //עדכון הרשאות למשתמש
    Route::post('permissionUserShow/{idUser?}', [PermissionController::class ,'storeAjax'])->name('permuser.updateajax');

});


Route::group(['prefix' => 'dashboard', 'namespace' => 'Bank', 'middleware' => ['auth','permprog:dashboard.banklines']], function () {
    Route::get('state_bank_lines/{year?}', [DashboardController::class ,'bankLines'])->name('dashboard.banklines');
    //יתרות לכל עמותה - זכות חובה לכל חודש
    //נסגר צירך לבנות אחד חדש
    Route::get('balance/{id_projc}', [DashboardController::class ,'balance'])->name('dashboard.balance');
});

//Start project

Route::group(['prefix' => 'managetable/title', 'namespace' => 'Bank', 'middleware' => ['auth','permprog:table.title.show']], function () {
    //כותרת ראשית ותת כותרת ראשית
    //managetable/title/show
    Route::get('show', [TitleOneController::class ,'showTable'])->name('table.title.show');
    Route::post('store', [TitleOneController::class, 'store'])->name('table.title.store');
    Route::post('storetitletwo', [TitleTwoController::class, 'store'])->name('table.titletwo.store');
});

//enterprise

Route::group(['prefix' => 'managetable/enterprise', 'namespace' => 'Bank', 'middleware' => ['auth','permprog:table.enterprise.show']], function () {
    //قائمة المؤسسات والمشاريع
    //managetable/enterprise/show
    Route::get('show', [EnterpriseController::class ,'showTable'])->name('table.enterprise.show');
    Route::post('store', [EnterpriseController::class, 'store'])->name('table.enterprise.store');
    Route::post('storeproject', [ProjectsController::class, 'store'])->name('table.project.store');
});

Route::group(['prefix' => 'managetable/city', 'namespace' => 'Bank', 'middleware' => ['auth','permprog:table.city.show']], function () {
    //جدول البلدان
    //managetable/enterprise/show
    Route::get('show', [CityController::class ,'showTable'])->name('table.city.show');
    Route::post('store', [CityController::class, 'store'])->name('table.city.store');
});

Route::group(['prefix' => 'managetable/donatetype', 'namespace' => 'Bank', 'middleware' => ['auth','permprog:donateType.show']], function () {
    //סוג תרומה בשווה
    Route::get('donateType', [DonateTypeController::class,'donateType'])->name('donateType.show');
    //הוספת סוג חדש
    Route::post('store', [DonateTypeController::class,'store'])->name('donateType.store');
    //עדכון מחיר
    Route::put('store/{id_donatetype?}', [DonateTypeController::class, 'updatePriceAjax'])->name('donateType.updatepriceajax');

    //הודת קובץ סוגי תרומה
    Route::post('exportfile', [DonateTypeController::class,'DonateTypeExport'])->name('donateType.export');

    //יבוא קובץ סוג תרומה לעדכון
    Route::post('importfile', [DonateTypeController::class, 'DonateTypeImport'])->name('donateType.import');
});

/**
Route::group(['prefix' => 'managetable/connect_projects_city', 'namespace' => 'Bank', 'middleware' => ['auth']], function () {
    //ربط المشاريع مع البلدان
    //managetable/connect_projects_city/show
    Route::get('show', [ProjectsCityController::class ,'showTableWithProjectAndCity'])->name('table.connect_projects_city.show');
    //הצגה
    Route::get('show/{id}', [ProjectsCityController::class ,'showTableByProject'])->name('table.connect_projects_city.edit');
    //הוספת
    Route::post('show/{id}', [ProjectsCityController::class ,'store'])->name('table.connect_projects_city.store');
});
**/

Route::group(['prefix' => 'managetable/income', 'namespace' => 'Bank', 'middleware' => ['auth','permprog:table.income.show']], function () {
    //انواع المدخولات
    Route::get('show', [IncomeController::class ,'showTable'])->name('table.income.show');
    Route::post('store', [IncomeController::class ,'store'])->name('table.income.store');

    Route::delete('delete/{id_income}', [IncomeController::class ,'delete'])->name('table.income.delete');
    //מחזיר כל סוגי תרומה לפרויקט מסויים
    Route::get('income_by_project/{id_proj?}', [IncomeController::class ,'getByProjects'])->name('table.incomebyproject.store');
});

Route::group(['prefix' => 'managetable/expense', 'namespace' => 'Bank', 'middleware' => ['auth','permprog:table.expense.show']], function () {
    //انواع المصروفات - موارد
    //xxxxxxxxxxxxxxx
    Route::get('show', [ExpenseController::class ,'showTable'])->name('table.expense.show');
    Route::post('store', [ExpenseController::class ,'store'])->name('table.expense.store');

    Route::delete('delete/{id_income}', [ExpenseController::class ,'delete'])->name('table.expense.delete');

    //מחזיר כל שמות ספקים לפרויקט מסויים
    Route::get('expense_by_project/{id_proj?}', [ExpenseController::class ,'getByProjects'])->name('table.expensebyproject.store');


});

/**
 * תוכנה פוצלה לשתי מסכים
 * מסך אלד לסוגי הכנסה ועוד מסך לסוגי הוצאה שזה ספקים
 * ***/
Route::group(['prefix' => 'managetable/expense_income', 'namespace' => 'Bank', 'middleware' => ['auth','permprog:table.expense_income.show']], function () {
    //جدول المصروفات والمدخولات
    //managetable/enterprise/show
    Route::get('show', [ExpenseController::class ,'showExpenseAndIncome'])->name('table.expense_income.show');
    Route::post('storeexpense', [ExpenseController::class ,'store'])->name('table.expense.store');
    Route::post('storeincome', [IncomeController::class ,'store'])->name('table.income.store');

    //מחזיר כל סוגי תרומה לפרויקט מסויים
    Route::get('income_by_project/{id_proj?}', [IncomeController::class ,'getByProjects'])->name('table.incomebyproject.store');

    //מחזיר כל שמות ספקים לפרויקט מסויים
    Route::get('expense_by_project/{id_proj?}', [ExpenseController::class ,'getByProjects'])->name('table.expensebyproject.store');
});


Route::group(['prefix' => 'managetable/campaigns', 'namespace' => 'Bank', 'middleware' => ['auth','permprog:table.campaigns.show']], function () {
    //جدول الحملات للمشاريع
    Route::get('show/{id_projc}', [CampaignsController::class,'showTableById'])->name('table.campaigns.show');
    Route::post('store/{id_projc}', [CampaignsController::class ,'store'])->name('table.campaigns.store');

    //מחיקת שורה
    Route::delete('delete/{id_projc}/', [CampaignsController::class, 'delete'])->name('table.campaigns.delete');

});
/**
Route::group(['prefix' => 'managetable/income', 'namespace' => 'Bank', 'middleware' => ['auth']], function () {
    //הוצאות
    //managetable/income/show
    Route::get('show', [IncomeController::class ,'show'])->name('table.income.show');
    //הצגה
    Route::get('show/{id}', [IncomeController::class ,'showById'])->name('table.income.edit');
    //הוספת
    Route::post('show/{id}', [IncomeController::class ,'store'])->name('table.income.store');
});

Route::group(['prefix' => 'managetable/expense', 'namespace' => 'Bank', 'middleware' => ['auth']], function () {
    //הכנסות
    //managetable/income/show
    Route::get('show', [ExpenseController::class ,'show'])->name('table.expense.show');
    //הצגה
    Route::get('show/{id}', [ExpenseController::class ,'showById'])->name('table.expense.edit');
    //הוספת
    Route::post('show/{id}', [ExpenseController::class ,'store'])->name('table.expense.store');
});
**/

Route::group(['prefix' => 'reports/structure_association', 'namespace' => 'Bank', 'middleware' => ['auth','permprog:reports.structure']], function () {
    //מבנה העמותה
    Route::get('show', [EnterpriseController::class ,'showTableStructure'])->name('reports.structure');//

    //הצגת עיר מקושרת לפרויקט
    Route::get('showProjectCity/{id}', [ProjectsController::class ,'showCityByProject'])->name('table.connect_projects_city.edit');
    //שמירה קישור בין עיר לפרויקט
    Route::post('showProjectCity/{id}', [ProjectsController::class ,'storeProjectCity'])->name('table.connect_projects_city.store');

    //הצגת הכנסה מקושרת לפרויקט
    Route::get('showProjectExpense/{id}', [ProjectsController::class ,'showExpenseByProject'])->name('table.connect_projects_expense.edit');
    //שמירה קישור בין הכנסה לפרויקט
    Route::post('showProjectExpense/{id}', [ProjectsController::class ,'storeProjectExpense'])->name('table.connect_projects_expense.store');

    //הצגת הוצאות מקושרת לפרויקט
    Route::get('showProjectIncome/{id}', [ProjectsController::class ,'showIncomeByProject'])->name('table.connect_projects_income.edit');
    //שמירה קישור בין הוצאה לפרויקט
    Route::post('showProjectIncome/{id}', [ProjectsController::class ,'storeProjectIncome'])->name('table.connect_projects_income.store');

});


Route::group(['prefix' => 'managebanks/listbanks', 'namespace' => 'Bank', 'middleware' => ['auth' ,'permprog:banks.show']], function () {
    //جدول البنوك
    //managebanks/listbanks/show
    Route::get('show/{id_bank?}', [BanksController::class ,'showTable'])->name('banks.show');
    Route::post('store/{id_bank}', [BanksController::class, 'store'])->name('banks.store');
});

Route::group(['prefix' => 'managebanks/csvbanks', 'namespace' => 'Bank', 'middleware' => ['auth','permprog:banks.mainLoadCsv']], function () {
    //מסך ראשי להעלאה קובץ CSV לבנק
    Route::get('storecsv', [BanksController::class, 'mainLoadCsv'])->name('banks.mainLoadCsv');
    //העלאת קובץ CSV
    Route::post('storecsv', [BanksController::class, 'storeFileCsv'])->name('banks.storeFileCsv');
});


Route::group(['prefix' => 'usb/report_income_expense', 'namespace' => 'Usb', 'middleware' => ['auth','permprog:usb_report.show']], function () {
    //USB דוח סיכום להכנסות/הוצאות לעמותה
    Route::get('show/{id_entrep?}', [UsbIncomeController::class ,'showReport'])->name('usb_report.show');

});


/**
Route::group(['prefix' => 'zaka/{id_entrep}/{id_city}/{id_proj}/{flgzaka?}', 'namespace' => 'Usb', 'middleware' => ['auth','permprog:zaka.show']], function () {
    //תיעוד הכנסות - USB - בחירת פרויקט
    //خاص للزكاه =1
    Route::get('show', [UsbIncomeController::class ,'index_entrep'])->name('zaka.show');
});
**/

Route::group(['prefix' => 'usb_income_entrep/{id_entrep}/{id_city}/{id_proj?}/{flgzaka?}', 'namespace' => 'Usb', 'middleware' => ['auth','permprog:usb_income_entrep.show']], function () {
    //תיעוד הכנסות - USB - בחירת פרויקט
    Route::get('/', [UsbIncomeController::class ,'index_entrep'])->name('usb_income_entrep.show');
});


Route::group(['prefix' => 'usb_income/{id_entrep?}/{id_proj?}/{id_city?}', 'namespace' => 'Usb', 'middleware' => ['auth' ]], function () {
    //תיעוד הכנסות - USB
    Route::get('show', [UsbIncomeController::class ,'index'])->name('usb_income.show');
    //INSERT
    Route::post('store', [UsbIncomeController::class, 'storeAjax'])->name('usb_income.storeajax');
    //EDIT
    Route::get('store/{uuid_usbincome?}', [UsbIncomeController::class, 'editAjax'])->name('usb_income.editajax');
    //UPDATE
    Route::put('update/{uuid_usbincome?}', [UsbIncomeController::class, 'updateajax'])->name('usb_income.updateajax');
    //DELETE
    Route::delete('delete/{uuid_usbincome?}', [UsbIncomeController::class, 'deleteAjax'])->name('usb_income.deleteajax');
    //report - סיכום
    //Route::get('showreport/{FromDate?}/{ToDate?}', [UsbIncomeController::class ,'showReport'])->name('usb_income.show.report');

    //הצגת קבלה לפי מספר שלה
    Route::get('showKabala', [UsbIncomeController::class ,'showKabala'])->name('usb_income.showKabala');

    Route::post('exportData', [UsbIncomeController::class ,'exportData'])->name('usb_income.exportData');




});

Route::group(['prefix' => 'usb_expense_entrep/{id_entrep}/{id_city}/{id_proj?}/{flgfeter?}', 'namespace' => 'Usb', 'middleware' => ['auth','permprog:usb_expense_entrep.show']], function () {
    //תיעוד הוצאות - USB - בחירת פרויקט
    Route::get('/', [UsbExpenseController::class ,'index_entrep'])->name('usb_expense_entrep.show');

});


Route::group(['prefix' => 'usb_expense/{id_entrep?}/{id_proj?}/{id_city?}', 'namespace' => 'Usb', 'middleware' => ['auth']], function () {
    //תיעוד הוצאות - USB
    Route::get('show', [UsbExpenseController::class ,'index'])->name('usb_expense.show');
    //INSERT
    Route::post('store', [UsbExpenseController::class, 'storeAjax'])->name('usb_expense.storeajax');
    //EDIT
    Route::get('store/{uuid_usbexpense?}', [UsbExpenseController::class, 'editAjax'])->name('usb_expense.editajax');
    //UPDATE
    Route::put('update/{uuid_usbexpense?}', [UsbExpenseController::class, 'updateajax'])->name('usb_expense.updateajax');
    //DELETE
    Route::delete('delete/{uuid_usbexpense?}', [UsbExpenseController::class, 'deleteAjax'])->name('usb_expense.deleteajax');
    //report - סיכום
    //Route::get('showreport/{FromDate?}/{ToDate?}', [UsbExpenseController::class ,'showReport'])->name('usb_expense.show.report');

});

Route::group(['prefix' => 'donate/{id_entrep}/{id_proj}/{id_city}', 'namespace' => 'Bank', 'middleware' => ['auth','permprog:mainDonate.show']], function () {
    //תרומב בשווה - כניסה לעמות + פרויקט
    //מסך ראשי - תרומה בשווה
    Route::get('/', [DonateworthController::class,'mainDonate'])->name('mainDonate.show');
    //INSERT
    Route::post('store', [DonateworthController::class, 'storeAjax'])->name('mainDonate.storeajax');
    //EDIT
    Route::get('store/{id_donate?}', [DonateworthController::class, 'editAjax'])->name('mainDonate.editajax');
    //UPDATE
    Route::put('store/{id_donate?}', [DonateworthController::class, 'updateAjax'])->name('mainDonate.updateajax');
    //DELETE
    Route::delete('delete/{id_donate?}', [DonateworthController::class, 'deleteAjax'])->name('mainDonate.deleteajax');

});


Route::group(['prefix' => 'editadahi/{id_city?}', 'namespace' => 'Usb', 'middleware' => ['auth','permprog:adahi.show']], function () {
    //اضاحي - USB
    Route::get('show', [AdahiController::class ,'index'])->name('adahi.show');
    //INSERT
    Route::post('store', [AdahiController::class, 'storeAjax'])->name('adahi.storeajax');
    //EDIT
     Route::get('store/{uuid_adahi?}', [AdahiController::class, 'editAjax'])->name('adahi.editajax');
    //UPDATE
    Route::put('update/{uuid_adahi?}', [AdahiController::class, 'updateAjax'])->name('adahi.updateajax');
    //DELETE
    Route::delete('delete/{uuid_adahi?}', [AdahiController::class, 'deleteAjax'])->name('adahi.deleteajax');
    //report - סיכום
     Route::get('showreport/{FromDate?}/{ToDate?}', [AdahiController::class ,'showReport'])->name('adahi.show.report');
    //UsbExpenseController

});

Route::group(['prefix' => 'adahi/report', 'namespace' => 'Usb', 'middleware' => ['auth','permprog:adahi_report.show']], function () {
    //USB דוח סיכום להכנסות/הוצאות לעמותה
    Route::get('show', [AdahiController::class ,'showReport'])->name('adahi_report.show');

});

Route::group(['prefix' =>'export_import' , 'namespace' => 'Bank', 'middleware' => ['auth']], function () {
    //יבוא ויצאי קבצים
    Route::get('main', [ExportImportController::class,'mainDonateExportImport'])->name('export_import');
    //הודת קובץ
    Route::post('export', [ExportImportController::class,'mainExport'])->name('export_import.export');
    //העלאת קובץ CSV
    Route::post('import', [ExportImportController::class, 'mainImport'])->name('export_import.import');
});

/**
 *לא עובד - הועבר לשיטיה חדשה -הכל במסך אחד
 *
Route::group(['prefix' => 'donate/export_import', 'namespace' => 'Bank', 'middleware' => ['auth']], function () {
    //יבוא ויצאי קבצי תרומב בשווי
    Route::get('maindonate', [DonateworthController::class,'mainDonateExportImport'])->name('mainDonate.exportimport');
    //הודת קובץ תרומה בשווה
    Route::post('exportfile', [DonateworthController::class,'mainDonateExport'])->name('mainDonate.export');
    //העלאת קובץ
    Route::post('importfile', [DonateworthController::class, 'mainDonateImport'])->name('mainDonate.import');
});
**/

Route::group(['prefix' => 'managebanks/linebanks', 'namespace' => 'Bank', 'middleware' => ['auth','permprog:linebanks.show']], function () {
    //תנועות בחשבון - שורות בנק
    //managebanks/linebanks/show
    ///{from_date?}/{to_date?}
    Route::get('show/{id_bank}', [BankslineController::class ,'showTable'])->name('linebanks.show');
    Route::post('store/{id_bank}', [BankslineController::class, 'storeAjax'])->name('linebanks.storeajax');

    Route::get('store/{id_bank}/{id_line?}', [BankslineController::class, 'editAjax'])->name('linebanks.editajax');
    Route::put('store/{id_bank}/{id_line?}', [BankslineController::class, 'updateAjax'])->name('linebanks.updateajax');
    //מחיקת שורה
    Route::delete('delete/{id_bank}/{id_line?}', [BankslineController::class, 'deleteAjax'])->name('linebanks.deleteajax');
    //סימון שורה לא כפולה
    Route::put('noduplicate/{id_bank}/{id_line?}', [BankslineController::class, 'noduplicateAjax'])->name('linebanks.noduplicateajax');

    //צריך להוריד אם עוברים לשיטיה החדשה - דף ספיציפי להעלאה מסמך CSV
    //העלאת קובץ CSV
    //Route::post('storecsv/{id_bank}', [BankslineController::class, 'storeFileCsv'])->name('linebanks.storeFileCsv');

    //עדכון גורף לסוג תנועה או עמותה
    Route::post('updateselect/{id_bank}', [BankslineController::class, 'storeSelect'])->name('linebanks.storeselecttitle');

    //מחזיר שורה HTML - לחלוקת שורות
    Route::get('showrowdetils/{id_bank}/{id_line?}', [BankslineController::class, 'showrowdetilshtml'])->name('linebanks.showrowdetils');

    Route::post('story/{id_bank}/{id_line?}', [BanksdetailController::class ,'storeMultiRowAjax'])->name('linedetail.storemultirowajax');
});


Route::group(['prefix' => 'managebanks/help_chosse_type_line/{id_bank}', 'namespace' => 'Bank', 'middleware' => ['auth']], function () {
    //עזרה בשיוך סוג שורת בנק
    Route::get('show', [BankslineController::class ,'showTableNoTypeLine'])->name('notypeline.show');

    Route::post('story', [BankslineController::class ,'storeTypeLine'])->name('notypeline.storetypeline');

});

Route::group(['prefix' => 'managebanks/linedetail', 'namespace' => 'Bank', 'middleware' => ['auth','permprog:linedetail.show']], function () {
    //פירוט שורות בנק
    Route::get('show/{id_line}', [BanksdetailController::class ,'showTable'])->name('linedetail.show');
    Route::post('story/{id_line}', [BanksdetailController::class ,'storeAjax'])->name('linedetail.storeajax');

    Route::get('store/{id_line}/{id_detail?}', [BanksdetailController::class, 'editAjax'])->name('linedetail.editajax');
    Route::put('store/{id_line}/{id_detail?}', [BanksdetailController::class, 'updateAjax'])->name('linedetail.updateajax');

    //מחיקת שורה
    Route::delete('delete/{id_line}/{id_detail?}', [BanksdetailController::class, 'deleteAjax'])->name('linedetail.deleteajax');

    Route::post('show/autocomplate/{id_proj?}', [ProjectsController::class ,'getCityIcomeExpenseByProject'])->name('autocomplate.city.income.expense');

    //שמירת חלוקת השורות
    Route::post('storedivline/{id_line}', [BanksdetailController::class ,'storeDivDetail'])->name('linedetail.storedivline');

    //הצגת שורה דומה
    Route::get('showsameline/{id_line?}', [BanksdetailController::class ,'showSameLine'])->name('linedetail.sameline');

});

Route::group(['prefix' => 'reports/reportbank', 'namespace' => 'Bank', 'middleware' => ['auth']], function () {
    //تقارير بنكية
    Route::get('searchNew', [ReportbankController::class ,'mainPageNew'])->middleware('permprog:reports.banksearch.new')->name('reports.banksearch.new');
    //تقارير مشاريع
    Route::get('searchProj', [ReportProjectController::class ,'mainPageProj'])->middleware('permprog:reports.projsearch')->name('reports.projsearch');
    //تقرير شامل
    Route::get('searchFinal', [ReportProjectController::class ,'mainPageFinal'])->middleware('permprog:reports.finalall')->name('reports.finalall');
});
