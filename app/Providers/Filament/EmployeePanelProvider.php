<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\HtmlString;
use Filament\Navigation\MenuItem;
use App\Models\Faq;
use Illuminate\Support\Facades\Storage;

class EmployeePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('employee')
            ->path('employee')
            ->colors([
                'primary' => '#1E9CD7',
                'gray' => Color::Slate,
            ])
            ->brandLogo(new HtmlString("
                <div style='margin:0 0 0 45px;'>
                    <img src='" . asset('images/logo.png') . "' style='height:3.7rem;'>
                </div>
            "))
            ->sidebarWidth('260px')
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('My Profile')
                    ->icon('heroicon-o-user-circle')
                    ->url(fn(): string => \App\Filament\Employee\Pages\MyProfile::getUrl()),
            ])
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverResources(in: app_path('Filament/Employee/Resources'), for: 'App\\Filament\\Employee\\Resources')
            ->discoverPages(in: app_path('Filament/Employee/Pages'), for: 'App\\Filament\\Employee\\Pages')
            ->discoverWidgets(in: app_path('Filament/Employee/Widgets'), for: 'App\\Filament\\Employee\\Widgets')

            ->widgets([
                \App\Filament\Employee\Widgets\TrainingStats::class,
            ])
            ->renderHook(
                'panels::head.start',
                fn(): \Illuminate\Support\HtmlString => new \Illuminate\Support\HtmlString("
                    <link rel='shortcut icon' href='" . asset('images/logo.png') . "' type='image/x-icon'>
                ")
            )
            ->renderHook(
                'panels::head.end',
                fn(): string => new HtmlString("
                    <style>
                        .fi-topbar, .fi-topbar nav, .fi-sidebar-header {
                            background-color: transparent !important;
                            box-shadow: none !important;
                            border: none !important;
                            backdrop-filter: none !important;
                        }
                        .fi-topbar { position: sticky; top: 0; z-index: 40; transition: transform 0.3s ease-in-out; }
                        .fi-topbar-hidden { transform: translateY(-100%); }

                        .isro-help-btn {
                            background-color: #3BAFF2; color: white; border: none; 
                            padding: 6px 20px; border-radius: 6px; font-weight: 600; 
                            margin-right: 15px; cursor: pointer; transition: 0.3s;
                        }
                        .isro-help-btn:hover { background-color: #1E9CD7; }
                        
                        #EmpHelpModal { display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); }
                        .help-box { background: #fff; margin: 5% auto; padding: 30px; border-radius: 12px; width: 65%; max-height: 85vh; overflow-y: auto; color: #333; box-shadow: 0 10px 30px rgba(0,0,0,0.3); position: relative; }
                        .help-header { font-size: 24px; font-weight: 800; color: #1E9CD7; border-bottom: 2px solid #f0f0f0; margin-bottom: 20px; padding-bottom: 10px; }
                        
                        .help-tabs { display: flex; border-bottom: 2px solid #f0f0f0; margin-bottom: 20px; gap: 15px; }
                        .tab-link { padding: 10px 20px; cursor: pointer; font-weight: 700; color: #666; border-bottom: 3px solid transparent; }
                        .tab-link.active { color: #1E9CD7; border-bottom: 3px solid #1E9CD7; }
                        .tab-content { display: none; }
                        .tab-content.active { display: block; }

                        .help-section { margin-bottom: 20px; }
                        .help-section h3 { color: #003249; font-size: 18px; font-weight: 700; margin-bottom: 8px; }
                        .help-section ul { padding-left: 20px; }
                        .help-section li { margin-bottom: 5px; list-style-type: square; }

                        /* Footer / Button Styles */
                        .modal-footer { margin-top: 30px; padding-top: 20px; border-top: 2px solid #f0f0f0; display: flex; justify-content: flex-end; align-items: center; gap: 15px; }
                        .close-manual { background: #f1f5f9; color: #475569; padding: 10px 25px; border-radius: 6px; border: 1px solid #e2e8f0; cursor: pointer; font-weight: bold; transition: 0.2s; }
                        .close-manual:hover { background: #e2e8f0; }
                        
                        .download-guide-btn { background: #16a34a; color: white; padding: 10px 25px; border-radius: 6px; border: none; cursor: pointer; font-weight: bold; text-decoration: none; display: none; align-items: center; gap: 8px; transition: 0.2s; }
                        .download-guide-btn:hover { background: #15803d; }
                        
                        .faq-item { border: 1px solid #e5e7eb; border-radius: 10px; margin-bottom: 12px; overflow: hidden; }
                        .faq-question { padding: 14px 16px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-weight: 600; background: #f8fafc; }
                        .faq-icon { transition: transform 0.25s ease; }
                        .faq-item.active .faq-icon { transform: rotate(180deg); }
                        .faq-answer { display: none; padding: 14px 16px; border-top: 1px solid #e5e7eb; background: white; font-size: 14px; line-height: 1.6; }

                        .fi-main { display: flex; flex-direction: column; min-height: 100vh; }
                        footer { margin-top: auto; }
                    </style>
                ")
            )

            ->renderHook(
                'panels::user-menu.before',
                function (): string {
                    $latestManual = Faq::whereNotNull('attachment')->where('is_active', true)->latest()->first();
                    $downloadBtnHtml = "";
                    if ($latestManual) {
                        $fileUrl = Storage::url($latestManual->attachment);
                        $downloadBtnHtml = "
                            <a href='{$fileUrl}' id='manualDownloadBtn' target='_blank' download class='download-guide-btn'>
                                <svg style='width:18px; height:18px;' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'/>
                                </svg>
                                Download Guide
                            </a>
                        ";
                    }

                    return new HtmlString("
                        <button class='isro-help-btn' onclick='openHelpModal()'>Help</button>
                        <div id='EmpHelpModal' onclick='if(event.target == this) closeHelpModal()'>
                            <div class='help-box'>
                                <div class='help-header'>Employee Support Center</div>
                                
                                <div class='help-tabs'>
                                    <div class='tab-link active' onclick='openTab(event,\"guide-tab\")'>User Guide</div>
                                    <div class='tab-link' onclick='openTab(event,\"faq-tab\")'>FAQs</div>
                                </div>

                                <div id='guide-tab' class='tab-content active'>
                                    <div class='help-section'>
                                        <h3>1. Dashboard Overview</h3>
                                        <ul>
                                            <li>View your overall training statistics and current progress.</li>
                                            <li>Track pending requests that are currently with the Centre Coordinator or HQ.</li>
                                        </ul>
                                    </div>

                                    <div class='help-section'>
                                        <h3>2. Training History & Status</h3>
                                        <ul>
                                            <li>Go to <strong>'Training History'</strong> to see the list of programs you've applied for.</li>
                                            <li>Check the <strong>'Status'</strong> badge (Submitted, Approved, or Completed) for real-time updates.
                                            </li>
                                        </ul>
                                    </div>

                                    <div class='help-section'>
                                        <h3>3. Feedback & Ratings</h3>
                                        <ul>
                                            <li>Once a training is marked as <strong>'Completed'</strong>, you can provide your experience and rating.
                                            </li>
                                            <li>Your feedback helps the HQ improve future training programs.</li>
                                        </ul>
                                    </div>

                                    <div class='help-section'>
                                        <h3>4. Profile Management</h3>
                                        <ul>
                                            <li>Click on <strong>'My Profile'</strong> under your name to view your official details.</li>
                                            <li>Keep your contact information updated to receive official training notifications.</li>
                                        </ul>
                                    </div>
                                </div>

                                <div id='faq-tab' class='tab-content'>
                                    " . $this->getFaqsForHelp() . "
                                </div>

                                <div class='modal-footer'>
                                    {$downloadBtnHtml}
                                    <button class='close-manual' onclick='closeHelpModal()'>Close</button>
                                </div>
                            </div>
                        </div>
                    ");
                }
            )

            ->renderHook(
                'panels::body.end',
                fn(): string => new HtmlString("
                    <script>
                        function openHelpModal() {
                            document.getElementById('EmpHelpModal').style.display = 'block';
                            document.body.style.overflow = 'hidden'; 
                        }
                        function closeHelpModal() {
                            document.getElementById('EmpHelpModal').style.display = 'none';
                            document.body.style.overflow = 'auto';
                        }

                        function openTab(evt, tabName){
                            let contents = document.getElementsByClassName('tab-content');
                            for (let i = 0; i < contents.length; i++) { contents[i].classList.remove('active'); }
                            let links = document.getElementsByClassName('tab-link');
                            for (let i = 0; i < links.length; i++) { links[i].classList.remove('active'); }
                            document.getElementById(tabName).classList.add('active');
                            evt.currentTarget.classList.add('active');

                            const downloadBtn = document.getElementById('manualDownloadBtn');
                            if(downloadBtn) {
                                downloadBtn.style.display = (tabName === 'faq-tab') ? 'inline-flex' : 'none';
                            }
                        }

                        function toggleFaq(el){
                            const item = el.parentElement;
                            const answer = el.nextElementSibling;
                            const isCurrentlyActive = item.classList.contains('active');
                            if(isCurrentlyActive){
                                item.classList.remove('active');
                                answer.style.display = 'none';
                            } else {
                                item.classList.add('active');
                                answer.style.display = 'block';
                            }
                        }

                        let lastScrollTop = 0;
                        const topbar = document.querySelector('.fi-topbar');
                        window.addEventListener('scroll', function() {
                            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                            if (scrollTop > lastScrollTop && scrollTop > 50) {
                                if(topbar) topbar.classList.add('fi-topbar-hidden');
                            } else {
                                if(topbar) topbar.classList.remove('fi-topbar-hidden');
                            }
                            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
                        }, false);
                    </script>
                    <footer style='background-color: #003249; color: white; padding: 25px 20px; text-align: center; font-size: 13px; width: 100%;'>
                        <div style='margin-bottom: 12px;'>
                            <img src='" . asset('images/logo.png') . "' style='height: 40px; margin: 0 auto;'>
                        </div>
                        <p style='margin-bottom: 4px; font-weight: 500;'>Content on this website is managed and owned by Department of Space, Indian Space Research Organization, Government of India.</p>
                        <p style='opacity: 0.8;'>Powered by Capacity Building Programme Office, CBPO | ISRO.</p>
                    </footer>
                ")
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    private function getFaqsForHelp(): string
    {
        if (!class_exists('App\Models\Faq')) {
            return "<p style='padding:20px; text-align:center;'>FAQ system not found.</p>";
        }

        $faqs = Faq::where('is_active', true)
            ->whereNull('attachment')
            ->orderBy('sort_order')
            ->get();

        if ($faqs->isEmpty()) {
            return "<p style='padding:20px; text-align:center;'>No FAQs available.</p>";
        }

        $html = "";
        foreach ($faqs as $faq) {
            $html .= "
            <div class='faq-item'>
                <div class='faq-question' onclick='toggleFaq(this)'>
                    <span style='flex:1;'>{$faq->question}</span>
                    <span class='faq-icon'>▼</span>
                </div>
                <div class='faq-answer'>{$faq->answer}</div>
            </div>";
        }

        return $html;
    }
}