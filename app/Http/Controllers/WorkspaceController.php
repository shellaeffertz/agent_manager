<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\SupportTask;
use Illuminate\Http\Request;
use App\Models\DropManagerTask;
use App\Models\CompanyCreatorTask;
use App\Models\WebsiteCreatorTask;
use Illuminate\Support\Facades\Storage;
use App\Notifications\TelegramNotification;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WorkspaceController extends Controller
{
    public function index(Request $request) {

        if($request->user()->isDropManager()) {

            $todoTasks = SupportTask::query()->select('id', 'drop_id', 'verification_links', 'status')->with([
                'drop' => function($query) {
                    $query->select('id', 'fullname', 'documents', 'user_id');
                }])->where('is_sent', true)->whereHas('drop', function($query) use ($request){
                    $query->where('user_id', $request->user()->id);
                })->orderBy('created_at', 'DESC')->paginate(
                    $perPage = 10, $columns = ['*'], $pageName = 'verification_links'
                )->withQueryString();
                
            $tasks = DropManagerTask::where('user_id', $request->user()->id)->orderBy('created_at', 'DESC')->paginate(
                $perPage = 10, $columns = ['*'], $pageName = 'drops'
            )->withQueryString();

        } elseif($request->user()->isCompanyCreator()) {

            $todoTasks = DropManagerTask::query()->select('id', 'fullname', 'address', 'country', 'birthday', 'documents', 'status')->where('is_sent', true)->orderBy('created_at', 'DESC')->paginate(
                $perPage = 10, $columns = ['*'], $pageName = 'drops'
            )->withQueryString();
            $tasks = CompanyCreatorTask::where('user_id', $request->user()->id)->with(['drop' => function($query) {
                    $query->select('id', 'fullname');
                }])->orderBy('created_at', 'DESC')->paginate(
                    $perPage = 10, $columns = ['*'], $pageName = 'companies'
                )->withQueryString();

        } elseif($request->user()->isWebsiteCreator()) {

            $todoTasks = CompanyCreatorTask::query()->select('id', 'company_name', 'company_number', 'company_address', 'company_activity', 'status', 'drop_id')->with(['drop' => function($query) {
                    $query->select('id', 'fullname');
                }])->where('is_sent', true)->orderBy('created_at', 'DESC')->paginate(
                    $perPage = 10, $columns = ['*'], $pageName = 'companies'
                )->withQueryString();
            $tasks = WebsiteCreatorTask::where('user_id', $request->user()->id)->with([
                'drop' => function($query) {
                    $query->select('id', 'fullname');
                },
                'company' => function($query) {
                    $query->select('id', 'company_name');
                }])->orderBy('created_at', 'DESC')->paginate(
                    $perPage = 10, $columns = ['*'], $pageName = 'websites'
                )->withQueryString();

        } elseif($request->user()->isSupport()) {

            $todoTasks = WebsiteCreatorTask::query()->where('is_sent', true)->select('id', 'website_link', 'status', 'drop_id', 'company_id')->with([
                'drop' => function($query) {
                    $query->select('id', 'fullname', 'documents');
                },
                'company' => function($query) {
                    $query->select('id', 'company_name');
                }])->paginate(
                    $perPage = 10, $columns = ['*'], $pageName = 'websites'
                )->withQueryString();
            $tasks = SupportTask::where('user_id', $request->user()->id)->with([
                'drop' => function($query) {
                    $query->select('id', 'fullname');
                },
                'company' => function($query) {
                    $query->select('id', 'company_name');
                },
                'website' => function($query) {
                    $query->select('id', 'website_link');
                }])->orderBy('created_at', 'DESC')->paginate(
                    $perPage = 10, $columns = ['*'], $pageName = 'accounts'
                )->withQueryString();
        } elseif($request->user()->isAdministrator()) {
            $todoTasks = [];
            $tasks = [];
        }

        return Inertia::render('Workspace', [
            'todoTasks' => $todoTasks,
            'tasks' => $tasks
        ]);
    }

    public function store(Request $request) {

        if($request->user()->isDropManager()) {
            // Drop Manager

            $attributes = $request->validate([
                'fullname' => ['required', 'min:3'],
                'address' => ['required', 'min:3'],
                'country' => ['required', 'min:2'],
                'contact' => ['required', 'min:3'],
                'birthday' => ['required', 'date'],
                'documents' => ['required']
            ]);

            $filesPaths = [];
            foreach($request->file('documents') as $document) {

                $filesPaths[] = Storage::disk('public')->put('/documents', $document);
            }

            $attributes['documents'] = json_encode($filesPaths);
            $attributes['user_id'] = $request->user()->id;

            DropManagerTask::create($attributes);

            return redirect()->route('workspace')->banner('Drop Created Successfully');

        } elseif($request->user()->isCompanyCreator()) {
            // Company Creator

            $attributes = $request->validate([
                'drop_id' => ['required'],
                'company_name' => ['required', 'min:3'],
                'company_number' => ['required', 'min:3'],
                'company_address' => ['required', 'min:2'],
                'company_activity' => ['required', 'min:3'],
                'company_credentials' => ['required', 'min:10']
            ]);

            $attributes['user_id'] = $request->user()->id;

            CompanyCreatorTask::create($attributes);

            return redirect()->route('workspace')->banner('Company Created Successfully');

        } elseif($request->user()->isWebsiteCreator()) {
            // Website Creator

            $attributes = $request->validate([
                'drop_id' => ['required'],
                'company_id' => ['required'],
                'website_link' => ['required', 'min:3'],
                'website_credentials' => ['required', 'min:3']
            ]);

            $attributes['user_id'] = $request->user()->id;

            WebsiteCreatorTask::create($attributes);

            return redirect()->route('workspace')->banner('Website Created Successfully');

        } elseif($request->user()->isSupport()) {
            // Support

            $attributes = $request->validate([
                'drop_id' => ['required'],
                'company_id' => ['required'],
                'website_id' => ['required'],
                'account_name' => ['required', 'min:3'],
                'account_credentials' => ['required', 'min:3'],
            ]);

            $attributes['user_id'] = $request->user()->id;

            SupportTask::create($attributes);

            return redirect()->route('workspace')->banner('Account Created Successfully');
        }
    }

    public function update(Request $request) {

        if($request->user()->isDropManager()) {

            $drop = DropManagerTask::find($request->id);

            $attributes = $request->validate([
                'fullname' => ['required', 'min:3'],
                'address' => ['required', 'min:3'],
                'country' => ['required', 'min:2'],
                'contact' => ['required', 'min:3'],
                'birthday' => ['required', 'date'],
            ]);

            if($request->hasFile('documents')) {

                foreach($drop->documents as $document) {

                    Storage::disk('public')->delete($document);
                }

                $filesPaths = [];

                foreach($request->file('documents') as $document) {

                    $filesPaths[] = Storage::disk('public')->put('/documents', $document);
                }

                $attributes['documents'] = json_encode($filesPaths);
            }

            $drop->update($attributes);

            return redirect()->route('workspace')->banner('Drop Updated Successfully');

        } elseif($request->user()->isCompanyCreator()) {
            // Company Creator

            $company = CompanyCreatorTask::find($request->id);

            $attributes = $request->validate([
                'company_name' => ['required', 'min:3'],
                'company_number' => ['required', 'min:3'],
                'company_address' => ['required', 'min:2'],
                'company_activity' => ['required', 'min:3'],
                'company_credentials' => ['required', 'min:10']
            ]);

            $company->update($attributes);

            return redirect()->route('workspace')->banner('Company Updated Successfully');

        } elseif($request->user()->isWebsiteCreator()) {
            // Website Creator

            $website = WebsiteCreatorTask::find($request->id);

            $attributes = $request->validate([
                'website_link' => ['required', 'min:3'],
                'website_credentials' => ['required', 'min:10']
            ]);

            $website->update($attributes);

            return redirect()->route('workspace')->banner('Website Updated Successfully');

        } elseif($request->user()->isSupport()) {
            // Support

            $account = SupportTask::find($request->id);

            $attributes = $request->validate([
                'account_name' => ['required', 'min:3'],
                'account_credentials' => ['required', 'min:10']
            ]);

            $account->update($attributes);

            return redirect()->route('workspace')->banner('Account Updated Successfully');
        }

    }

    public function destroy(Request $request) {

        if($request->user()->isDropManager()) {

            $drop = DropManagerTask::find($request->id);

            $drop->delete();

            return redirect()->route('workspace')->banner('Drop Deleted Successfully');

        } elseif($request->user()->isCompanyCreator()) {
            // Company Creator

            $company = CompanyCreatorTask::find($request->id);

            $company->delete();

            return redirect()->route('workspace')->banner('Company Deleted Successfully');

        } elseif($request->user()->isWebsiteCreator()) {
            // Website Creator

            $website = WebsiteCreatorTask::find($request->id);

            $website->delete();

            return redirect()->route('workspace')->banner('Website Deleted Successfully');

        } elseif($request->user()->isSupport()) {
            // Support

            $account = SupportTask::find($request->id);

            $account->delete();

            return redirect()->route('workspace')->banner('Account Deleted Successfully');
        }
    }

    public function send(Request $request) {

        if($request->user()->isDropManager()) {

            $drop = DropManagerTask::find($request->id);

            $drop->update([
                'is_sent' => true,
                'status' => 'On Hold'
            ]);

            // send notification to company creator
            foreach(User::where('role', 'company_creator')->get() as $user) {
                if($user->telegram_chat_id) {
                    $user->notify(new TelegramNotification('You have a new Drop added'));
                }
            }

            return redirect()->route('workspace')->banner('Task Sent Successfully');

        } elseif($request->user()->isCompanyCreator()) {
            // Company Creator

            $company = CompanyCreatorTask::find($request->id);

            $company->update([
                'is_sent' => true,
                'status' => 'On Hold'
            ]);

            // send notification to website creator
            foreach(User::where('role', 'website_creator')->get() as $user) {
                if($user->telegram_chat_id) {
                    $user->notify(new TelegramNotification('You have a new Company added'));
                }
            }

            return redirect()->route('workspace')->banner('Task Sent Successfully');

        } elseif($request->user()->isWebsiteCreator()) {
            // Website Creator

            $website = WebsiteCreatorTask::find($request->id);

            $website->update([
                'is_sent' => true,
                'status' => 'On Hold'
            ]);

            // send notification to website creator
            foreach(User::where('role', 'support')->get() as $user) {
                if($user->telegram_chat_id) {
                    $user->notify(new TelegramNotification('You have a new Website added'));
                }
            }

            return redirect()->route('workspace')->banner('Task Sent Successfully');

        } elseif($request->user()->isSupport()) {
            // Support

            $account = SupportTask::find($request->id);

            $account->update([
                'is_sent' => true,
                'status' => 'On Hold'
            ]);

            // send notification to drop manager
            foreach(User::where('role', 'drop_manager')->get() as $user) {
                if($user->telegram_chat_id) {
                    $user->notify(new TelegramNotification('You have a new verification link added'));
                }
            }

            return redirect()->route('workspace')->banner('Task Sent Successfully');
        }

    }

    public function reject(Request $request) {

        if($request->user()->isDropManager()) {


        } elseif($request->user()->isCompanyCreator()) {
            // Company Creator

            $drop = DropManagerTask::find($request->id);

            $drop->update([
                'status' => 'Rejected',
                'is_sent' => false
            ]);

            // send notification to Drop Manager
            if($drop->user->telegram_chat_id) {
                $drop->user->notify(new TelegramNotification('You have a rejected Drop!'));
            }

            return redirect()->route('workspace')->banner('Drop rejected Successfully');

        } elseif($request->user()->isWebsiteCreator()) {
            // Website Creator

            $company = CompanyCreatorTask::find($request->id);

            $company->update([
                'status' => 'Rejected',
                'is_sent' => false
            ]);

            // send notification to company creator
            if($company->user->telegram_chat_id) {
                $company->user->notify(new TelegramNotification('You have a rejected Company!'));
            }

            return redirect()->route('workspace')->banner('Company rejected Successfully');

        } elseif($request->user()->isSupport()) {
            // Support

            $website = WebsiteCreatorTask::find($request->id);

            $website->update([
                'status' => 'Rejected',
                'is_sent' => false
            ]);

            // send notification to website creator
            if($website->user->telegram_chat_id) {
                $website->user->notify(new TelegramNotification('You have a rejected Website!'));
            }

            return redirect()->route('workspace')->banner('Website rejected Successfully');
        }

    }

    public function finish(Request $request) {

        if($request->user()->isDropManager()) {


        } elseif($request->user()->isCompanyCreator()) {
            // Company Creator

            $drop = DropManagerTask::find($request->id);

            $drop->update([
                'status' => 'Done'
            ]);

            return redirect()->route('workspace')->banner('Drop rejected Successfully');

        } elseif($request->user()->isWebsiteCreator()) {
            // Website Creator

            $company = CompanyCreatorTask::find($request->id);

            $company->update([
                'status' => 'Done'
            ]);

            return redirect()->route('workspace')->banner('Company finished Successfully');

        } elseif($request->user()->isSupport()) {
            // Support

            $account = WebsiteCreatorTask::find($request->id);

            $account->update([
                'status' => 'Done'
            ]);

            return redirect()->route('workspace')->banner('Task finished Successfully');
        }

    }

    public function storeVerificationLink(Request $request) {

        if($request->user()->isSupport()) {
            // Support

            $attributes = $request->validate([
                'verification_link' => ['required']
            ]);

            $account = SupportTask::find($request->id);
            
            $links = [];

            foreach($account->verification_links as $link) {
                $links[] = [
                    'link' => $link->link,
                    'status' => 'expired'
                ];
            }

            $links[] = [
                'link' => $attributes['verification_link'],
                'status' => 'On Hold'
            ];

            $account->update([
                'verification_links' => json_encode($links)
            ]);

            return redirect()->route('workspace')->banner('Link added Successfully');
        }

    }

    public function verifyLink(Request $request) {

        if($request->user()->isDropManager()) {
            
            $request->validate([
                'link' => ['required']
            ]);

            $links = [];

            $account = SupportTask::where('id', $request->link['id'])->first();

            $links = $account->verification_links;

            foreach($links as $link) {
                if($link->status ==  "On Hold") {
                    $link->status = $request->link['status'];
                    break;
                }
            }

            $account->update([
                'verification_links' => json_encode($links)
            ]);

            return redirect()->route('workspace')->banner('Link marked as verified Successfully');
        }
    }

    public function finishProject(Request $request) {

        if($request->user()->isSupport()) {
            // Support

            $attributes = $request->validate([
                'id' => ['required'],
                'status' => ['required']
            ]);

            $account = SupportTask::find($request->id);

            $account->update([
                'status' => $request->status
            ]);

            return redirect()->route('workspace')->banner('Project Finished Successfully');
        }
    }

    public function downloadData(Request $request) {

        $website = WebsiteCreatorTask::where('id', $request->id)->first();

        $filePath = storage_path('app/temp-file.txt');

        $file = fopen($filePath, 'w+');

        fwrite($file, "Drop Information :" . PHP_EOL);
        fwrite($file, "Full Name : " . $website->drop->fullname . PHP_EOL);
        fwrite($file, "Address : " . $website->drop->address . PHP_EOL);
        fwrite($file, "Country : " . $website->drop->country . PHP_EOL);
        fwrite($file, "Birthday : " . $website->drop->birthday . PHP_EOL);
        fwrite($file, "" . PHP_EOL);
        fwrite($file, "" . PHP_EOL);
        fwrite($file, "Company Infomation :" . PHP_EOL);
        fwrite($file, "Company Name :" . $website->company->company_name . PHP_EOL);
        fwrite($file, "Company Number :" . $website->company->company_number . PHP_EOL);
        fwrite($file, "Company Address :" . $website->company->company_address . PHP_EOL);
        fwrite($file, "Company Activity :" . $website->company->company_activity . PHP_EOL);
        fwrite($file, "" . PHP_EOL);
        fwrite($file, "" . PHP_EOL);
        fwrite($file, "Website Infomation :" . PHP_EOL);
        fwrite($file, "Website Infomation :" . $website->website_link . PHP_EOL);
 
        fclose($file);
 
        return response()->download($filePath, 'data.txt')->deleteFileAfterSend(true);
    }
}
