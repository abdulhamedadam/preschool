<x-filament::page>
    <div class="py-8 space-y-10">
        <!-- Your existing code remains unchanged -->
        @if($class_rooms->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- إجمالي الفصول -->
            <div class="stats-card" style="--card-color: #e74c3c;">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium opacity-90">{{ __('common.total_classrooms') }}</h3>
                        <p class="text-3xl font-bold mt-2">{{ $class_rooms->count() }}</p>
                    </div>
                    <div class="stats-icon">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.84l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- إجمالي الطلاب -->
            <div class="stats-card" style="--card-color: #3498db;">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium opacity-90">{{ __('common.total_students') }}</h3>
                        <p class="text-3xl font-bold mt-2">
                            {{ $class_rooms->sum(function($room) { return $room->teacher->students->count(); }) }}
                        </p>
                    </div>
                    <div class="stats-icon">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- المعلمين النشطين -->
            <div class="stats-card" style="--card-color: #27ae60;">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium opacity-90">{{ __('common.total_teachers') }}</h3>
                        <p class="text-3xl font-bold mt-2">{{ $class_rooms->unique('teacher_id')->count() }}</p>
                    </div>
                    <div class="stats-icon">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Classrooms Grid -->
        <div style="margin-top: 20px;">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                @foreach($class_rooms as $class_room)
                <div class="classroom-card">
                    <div class="classroom-header">
                        <div class="relative z-10">
                            <div class="classroom-icon">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 ... " />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-1">{{ @$class_room->name }}</h3>

                        </div>
                    </div>

                    <div class="p-6 space-y-6 flex flex-col h-full">
                        <!-- Students Section -->
                        <div class="space-y-4 flex-1">
                            <div class="flex items-center justify-between">
                                <h5 class="text-lg font-semibold text-gray-800">{{ __('common.students') }}</h5>
                                <span class="student-count">
                                    {{ $class_room->teacher->students->count() }} طالب
                                </span>
                            </div>

                            @if(optional($class_room->teacher)->students->count() > 0)
                            <div class="students-grid">
                                @foreach($class_room->teacher->students->take(9) as $student)
                                <div class="student-card" data-student-id="{{ $student->id }}" data-student-name="{{ $student->student->name }}">
                                    <div class="student-options">
                                        <button class="options-btn" onclick="toggleOptionsMenu(event, {{ $student->id }})">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>
                                        <div class="options-menu" id="options-menu-{{ $student->id }}">
                                            <button onclick="showMoveClassModal({{ $student->id }}, '{{ $student->student->name }}')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                </svg>
                                                نقل إلى فصل آخر
                                            </button>
                                            <button onclick="showStudentDetails({{ $student->id }}, '{{ $student->student->name }}')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                عرض التفاصيل
                                            </button>
                                        </div>
                                    </div>
                                    <div class="student-avatar" style="background: {{ ['#e74c3c','#3498db','#27ae60','#f39c12','#8e44ad','#16a085'][array_rand(['#e74c3c','#3498db','#27ae60','#f39c12','#8e44ad','#16a085'])] }};">
                                        {{ mb_substr($student->student->name ?? 'ط', 0, 1) }}
                                    </div>
                                    <span class="student-name">
                                        {{ $student->student->name }}
                                    </span>
                                </div>
                                @endforeach

                                @if($class_room->teacher->students->count() > 9)
                                <div class="more-students">
                                    +{{ $class_room->teacher->students->count() - 9 }} المزيد
                                </div>
                                @endif
                            </div>
                            @else
                            <div class="no-students">
                                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 
                                                    0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 
                                                    2.5 0 11-5 0 2.5 2.5 0 015 
                                                    0z" />
                                </svg>
                                <p class="text-sm">لا يوجد طلاب في هذا الفصل</p>
                            </div>
                            @endif
                        </div>

                        <!-- Add Student Button pinned at bottom -->
                        <div class="pt-4 border-t border-gray-100 mt-auto">
                            <x-filament::button
                                icon="heroicon-o-user-plus"
                                wire:click="$dispatch('openModal', { component: 'add-student', arguments: { classRoom: {{ $class_room->id }} }})"
                                size="sm"
                                class="w-full text-white font-bold add-student-btn">
                                {{ __('common.add_student') }}
                            </x-filament::button>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>
        </div>

        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">
                <svg class="w-16 h-16 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">لا توجد فصول دراسية بعد</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto text-lg leading-relaxed">
                ابدأ رحلتك التعليمية بإنشاء فصل دراسي جديد وإضافة الطلاب والمعلمين
            </p>
            <x-filament::button
                icon="heroicon-o-plus"
                wire:click="$dispatch('openModal', { component: 'create-classroom' })"
                size="lg"
                class="create-class-btn">
                إنشاء فصل دراسي جديد
            </x-filament::button>
        </div>
        @endif
    </div>

    <!-- Move Class Modal (Hidden by default) -->
    <div id="moveClassModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">نقل الطالب إلى فصل آخر</h3>
                <button type="button" class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>سيتم نقل الطالب: <span id="studentName" class="font-semibold"></span></p>
                <div class="form-group">
                    <label for="classSelect">اختر الفصل الوجهة:</label>
                    <select id="classSelect" class="form-select">
                        <option value="">-- اختر الفصل --</option>
                        <option value="1">الصف الأول الابتدائي</option>
                        <option value="2">الصف الثاني الابتدائي</option>
                        <option value="3">الصف الثالث الابتدائي</option>
                        <option value="4">الصف الرابع الابتدائي</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="confirmMove()">تأكيد النقل</button>
            </div>
        </div>
    </div>

    <!-- Student Details Modal (Hidden by default) -->
    <div id="studentDetailsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">تفاصيل الطالب</h3>
                <button type="button" class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="student-details">
                    <div class="detail-row">
                        <div class="detail-label">الاسم:</div>
                        <div class="detail-value" id="detail-name"></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">العمر:</div>
                        <div class="detail-value" id="detail-age">8 سنوات</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">الفصل الحالي:</div>
                        <div class="detail-value" id="detail-class">الصف الثاني الابتدائي</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">المعلم:</div>
                        <div class="detail-value" id="detail-teacher">سارة أحمد</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">تاريخ التسجيل:</div>
                        <div class="detail-value" id="detail-date">01/09/2023</div>
                    </div>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-primary" onclick="closeModal()">حسناً</button>
            </div>
        </div>
    </div>

    <style>
        :root {
            --card-color: #3498db;
        }

        /* تحسين الخطوط */
        body {
            font-family: 'Tajawal', sans-serif;
        }

        /* بطاقات الإحصائيات */
        .stats-card {
            border-radius: 1rem;
            padding: 1.5rem;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            background: linear-gradient(135deg, var(--card-color), color-mix(in srgb, var(--card-color) 80%, black));
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.2), transparent);
            transform: rotate(-15deg);
        }

        .stats-icon {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
        }

        /* بطاقات الفصول الدراسية */
        .classroom-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 500px;
        }


        .classroom-header {
            padding: 1.5rem;
            text-align: center;
            position: relative;
            background: linear-gradient(135deg, #4a90e2, #9013fe);
        }

        .classroom-icon {
            width: 4rem;
            height: 4rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
        }

        /* شبكة الطلاب */
        .students-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .student-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            border-radius: 0.75rem;
            padding: 0.75rem;
            transition: all 0.2s ease;
            background: #f8f9fa;
            border: 1px solid #eaeaea;
            height: 120px;
            position: relative;
        }

        .student-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .student-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .student-name {
            font-size: 0.75rem;
            font-weight: 500;
            color: #374151;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 100%;
        }

        .student-count {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            background: #d6eaff;
            color: #0056b3;
        }

        .more-students {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            padding: 0.75rem;
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
            background: #f0f0f0;
            height: 80px;
            grid-column: span 3;
        }

        .no-students {
            text-align: center;
            padding: 2rem 0;
            color: #6b7280;
        }

        /* زر إضافة طالب */
        .add-student-btn {
            background: linear-gradient(90deg, #3498db, #9b59b6);
            border: 0;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }


        /* حالة عدم وجود فصول */
        .empty-state {
            text-align: center;
            padding: 5rem 0;
        }

        .empty-icon {
            margin: 0 auto 2rem;
            width: 8rem;
            height: 8rem;
            background: linear-gradient(to bottom right, #e0f2fe, #f0f9ff);
            border-radius: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
        }

        .create-class-btn {
            background: linear-gradient(to right, #3b82f6, #8b5cf6);
            border: 0;
            border-radius: 0.75rem;
            padding: 1rem 2rem;
            transition: all 0.3s ease;
        }

        /* Student Options Styles */
        .student-options {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
        }

        .options-btn {
            background: rgba(255, 255, 255, 0.8);
            border: none;
            border-radius: 0.25rem;
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .options-btn:hover {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.1);
        }

        .options-menu {
            position: absolute;
            top: 1.75rem;
            left: 0;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            width: 160px;
            display: none;
        }

        .options-menu button {
            width: 100%;
            padding: 0.5rem 0.75rem;
            text-align: right;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 0.75rem;
            transition: background 0.2s;
        }

        .options-menu button:hover {
            background: #f0f5ff;
        }

        .options-menu button svg {
            width: 1rem;
            height: 1rem;
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1001;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 1rem;
            width: 90%;
            max-width: 500px;
            padding: 1.5rem;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #999;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #555;
        }

        .form-select {
            width: 100%;
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #ddd;
            font-size: 1rem;
            background: white;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            margin-top: 1.25rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #4a90e2;
            color: white;
        }

        .btn-secondary {
            background: #e0e0e0;
            color: #555;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* Student Details */
        .student-details {
            margin-top: 1rem;
        }

        .detail-row {
            display: flex;
            margin-bottom: 0.75rem;
        }

        .detail-label {
            width: 120px;
            font-weight: 500;
            color: #555;
        }

        .detail-value {
            flex: 1;
            color: #333;
        }

        @media (prefers-color-scheme: dark) {
            .classroom-card {
                background: #1f2937;
                border-color: #374151;
            }

            .student-card {
                background: #374151;
                border-color: #4b5563;
            }

            .student-name {
                color: #e5e7eb;
            }

            .more-students {
                background: #374151;
                color: #9ca3af;
            }
            
            .options-btn {
                background: rgba(55, 65, 81, 0.8);
                color: #e5e7eb;
            }
            
            .options-menu {
                background: #1f2937;
                color: #e5e7eb;
            }
            
            .options-menu button:hover {
                background: #374151;
            }
            
            .modal-content {
                background: #1f2937;
                color: #e5e7eb;
            }
            
            .modal-title {
                color: #e5e7eb;
            }
            
            .form-select {
                background: #374151;
                color: #e5e7eb;
                border-color: #4b5563;
            }
            
            .detail-label, .detail-value {
                color: #e5e7eb;
            }
        }
    </style>

    <script>
        // Global variables to store current student info
        let currentStudentId = null;
        let currentStudentName = null;
        
        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Close options menus when clicking elsewhere
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.student-options')) {
                    const menus = document.querySelectorAll('.options-menu');
                    menus.forEach(menu => {
                        menu.style.display = 'none';
                    });
                }
            });
        });
        
        // Toggle options menu for a student
        function toggleOptionsMenu(event, studentId) {
            event.stopPropagation();
            
            // Hide all other menus
            const menus = document.querySelectorAll('.options-menu');
            menus.forEach(menu => {
                if (menu.id !== `options-menu-${studentId}`) {
                    menu.style.display = 'none';
                }
            });
            
            // Toggle current menu
            const menu = document.getElementById(`options-menu-${studentId}`);
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            
            // Store student info
            const studentCard = event.target.closest('.student-card');
            currentStudentId = studentId;
            currentStudentName = studentCard.getAttribute('data-student-name');
        }
        
        // Show move class modal
        function showMoveClassModal(studentId, studentName) {
            // Hide options menu
            const menu = document.getElementById(`options-menu-${studentId}`);
            menu.style.display = 'none';
            
            // Set student info
            currentStudentId = studentId;
            currentStudentName = studentName;
            
            // Show modal
            const modal = document.getElementById('moveClassModal');
            document.getElementById('studentName').textContent = currentStudentName;
            modal.style.display = 'flex';
        }
        
        // Show student details modal
        function showStudentDetails(studentId, studentName) {
            // Hide options menu
            const menu = document.getElementById(`options-menu-${studentId}`);
            menu.style.display = 'none';
            
            // Set student info
            currentStudentId = studentId;
            currentStudentName = studentName;
            
            // Show modal
            const modal = document.getElementById('studentDetailsModal');
            document.getElementById('detail-name').textContent = currentStudentName;
            modal.style.display = 'flex';
        }
        
        // Close modal
        function closeModal() {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.style.display = 'none';
            });
        }
        
        // Confirm move action
        function confirmMove() {
            const classSelect = document.getElementById('classSelect');
            const selectedClass = classSelect.value;
            
            if (!selectedClass) {
                alert('يرجى اختيار فصل');
                return;
            }
            
            // Here you would typically make an AJAX request to move the student
            alert(`تم نقل الطالب ${currentStudentName} إلى الفصل المحدد`);
            closeModal();
        }
    </script>
</x-filament::page>