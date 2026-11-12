@extends('admin.layouts.app')

@section('content')
    <div class="admin-page-heading">
        <div>
            <div class="admin-eyebrow">مدیریت دسترسی‌ها</div>
            <h1 class="admin-page-title">نقش‌ها و دسترسی‌ها</h1>
            <p class="admin-page-description">
                مشاهده نقش‌های تعریف‌شده و مسئولیت‌های هر نقش در سامانه پرچم سرخ.
            </p>
        </div>

        <div class="admin-page-heading-actions">
            <span class="admin-role-badge">
                {{ number_format(count($roles)) }} نقش تعریف‌شده
            </span>
        </div>
    </div>

    <div class="admin-empty-state" style="margin-bottom: 24px;">
        <h3>مدیریت نقش‌ها در حالت مشاهده</h3>
        <p>
            در حال حاضر نقش‌ها ثابت هستند و امکان ایجاد، ویرایش یا حذف نقش وجود ندارد.
        </p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(min(100%, 300px), 1fr)); gap:20px;">
        @foreach ($roles as $role)
            <section class="admin-panel" style="margin:0;">
                <div class="admin-panel-header">
                    <div>
                        <h2>{{ $role['name'] }}</h2>
                        <small style="color:#64748b; direction:ltr; display:inline-block;">
                            {{ $role['key'] }}
                        </small>
                    </div>

                    <span class="admin-role-badge">
                        نقش سامانه
                    </span>
                </div>

                <div style="padding:20px;">
                    <p style="color:#64748b; line-height:2; margin:0 0 20px;">
                        {{ $role['description'] }}
                    </p>

                    <h3 style="font-size:14px; font-weight:700; margin-bottom:14px;">
                        دسترسی‌ها و مسئولیت‌ها
                    </h3>

                    <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:12px;">
                        @foreach ($role['permissions'] as $permission)
                            <li style="display:flex; align-items:flex-start; gap:10px; line-height:1.8; font-size:13px;">
                                <span style="color:#15803d; font-weight:700;">✓</span>
                                <span>{{ $permission }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>
        @endforeach
    </div>
@endsection