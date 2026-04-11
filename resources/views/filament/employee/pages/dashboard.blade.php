<div id="isro-filament-dashboard">
    <style>
        /* Filament main padding reset for full-screen feel */
        .fi-main-ctn { max-width: none !important; }
        
        .dashboard-wrapper {
            background-color: #f5f5f5;
            min-height: 100vh;
            padding: 2rem;
            width: 100%;
        }

        .user-header-card {
            background-color: #d9f0f9;
            padding: 1.25rem 2rem;
            border-radius: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border: 1px solid #c4e5f2;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .stat-header {
            background-color: #1E9CD7;
            color: white;
            padding: 1rem;
            text-align: center;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
        }

        .stat-content {
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
        }

        .stat-value {
            font-size: 3rem;
            font-weight: 800;
            color: #1E9CD7;
        }

        .bars-container { display: flex; align-items: flex-end; gap: 4px; }
        .bar-item { width: 8px; border-radius: 2px; background: #e5e7eb; }
        .bar-active { background: #FF9800 !important; }

        .table-container {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .table-header-title {
            color: #1E9CD7;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .custom-table th {
            background-color: #f8fafc;
            padding: 1rem;
            text-align: left;
            font-weight: 700;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }
        .custom-table td { padding: 1.25rem 1rem; border-bottom: 1px solid #f1f5f9; }

        .apply-btn {
            background-color: #1E9CD7;
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 0.375rem;
            font-weight: 700;
            cursor: pointer;
        }
    </style>

    <div class="dashboard-wrapper">
        <div class="user-header-card">
            <div>
                <span class="text-slate-600 font-medium">Name: <b class="text-slate-900 ml-1">{{ auth()->user()->name }}</b></span>
            </div>
            <div class="text-right">
                <span class="text-slate-600 font-medium">Role: <b class="text-slate-900 ml-1 uppercase">{{ auth()->user()->designation ?? 'Scientist' }}</b></span><br>
                <span class="text-xs text-slate-400 italic">Last Login: {{ now()->format('d M, Y H:i') }}</span>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">Ongoing Training</div>
                <div class="stat-content">
                    <div class="bars-container"><div class="bar-item h-6"></div><div class="bar-item h-10 bar-active"></div><div class="bar-item h-8"></div></div>
                    <div class="stat-value">{{ $ongoingCount }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">Completed Training</div>
                <div class="stat-content">
                    <div class="relative w-12 h-12 border-4 border-[#FF9800] border-t-transparent rounded-full flex items-center justify-center">
                        <span class="text-[10px] font-bold text-[#FF9800]">%</span>
                    </div>
                    <div class="stat-value">{{ $completedCount }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">Un Approved Training</div>
                <div class="stat-content">
                    <div class="bars-container"><div class="bar-item h-4"></div><div class="bar-item h-7"></div><div class="bar-item h-10 bar-active"></div></div>
                    <div class="stat-value">{{ $unapprovedCount }}</div>
                </div>
            </div>
        </div>

        <div class="table-container">
            <h2 class="table-header-title">List of Training Calendar</h2>
            <div class="overflow-x-auto">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Training Name</th>
                            <th>Year</th>
                            <th>Attended From</th>
                            <th>Attended To</th>
                            <th>Location</th>
                            <th>Mode</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td class="font-semibold">Advanced Satellite Communication</td>
                            <td>2026</td>
                            <td>15 Feb 2026</td>
                            <td>20 Feb 2026</td>
                            <td>Bangalore</td>
                            <td>On-Site</td>
                            <td class="text-center">
                                <button class="apply-btn">Apply Now</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>