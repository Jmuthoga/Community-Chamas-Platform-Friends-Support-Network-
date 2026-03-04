@extends('backend.master')

@section('title', 'Create Announcement')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fas fa-bullhorn me-2"></i> Create Announcement
        </h3>
        <a href="{{ route('backend.admin.communications.announcements') }}" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Announcements
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('backend.admin.communications.announcements.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            {{-- Hidden inputs for database --}}
            <input type="hidden" name="send_email" id="input_send_email" value="0">
            <input type="hidden" name="send_sms" id="input_send_sms" value="0">

            {{-- Title --}}
            <div class="mb-4">
                <label for="title" class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                <input type="text" id="title" name="title" class="form-control form-control-lg" value="{{ old('title') }}" required>
                <div class="invalid-feedback">Title is required.</div>
            </div>

            {{-- Message --}}
            <div class="mb-4">
                <label for="body" class="form-label fw-bold">Message <span class="text-danger">*</span></label>
                <textarea id="body" name="body" class="form-control form-control-lg" rows="6" required>{{ old('body') }}</textarea>
                <div class="invalid-feedback">Message content is required.</div>
            </div>

            {{-- Notification Channel --}}
            <div class="mb-4">
                <label class="form-label fw-bold">Notification Channel</label>
                <div class="row">
                    {{-- None --}}
                    <div class="col-md-3">
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input" id="notify_none" checked>
                            <label class="custom-control-label" for="notify_none">None</label>
                        </div>
                    </div>
                    {{-- Email --}}
                    <div class="col-md-3">
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input notify-switch" id="notify_email">
                            <label class="custom-control-label" for="notify_email">Email</label>
                        </div>
                    </div>
                    {{-- SMS --}}
                    <div class="col-md-3">
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input notify-switch" id="notify_sms">
                            <label class="custom-control-label" for="notify_sms">SMS</label>
                        </div>
                    </div>
                    {{-- Both --}}
                    <div class="col-md-3">
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input" id="notify_both">
                            <label class="custom-control-label" for="notify_both">Both</label>
                        </div>
                    </div>
                </div>
                <div class="form-text mt-2">Select Email, SMS, Both, or None. None is selected by default.</div>
            </div>

            {{-- Submit Button --}}
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-lg bg-gradient-primary px-5">
                    <i class="fas fa-save me-2"></i> Save Announcement
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Client-side validation & notification logic --}}
<script>
(() => {
    'use strict';

    const emailSwitch = document.getElementById('notify_email');
    const smsSwitch = document.getElementById('notify_sms');
    const bothSwitch = document.getElementById('notify_both');
    const noneSwitch = document.getElementById('notify_none');

    const inputEmail = document.getElementById('input_send_email');
    const inputSms = document.getElementById('input_send_sms');

    // Update Both
    const updateBoth = () => {
        bothSwitch.checked = emailSwitch.checked && smsSwitch.checked;
    };

    // Update None
    const updateNone = () => {
        noneSwitch.checked = !emailSwitch.checked && !smsSwitch.checked;
    };

    // Email/SMS change
    [emailSwitch, smsSwitch].forEach(s => {
        s.addEventListener('change', () => {
            updateBoth();
            updateNone();
        });
    });

    // Both change
    bothSwitch.addEventListener('change', () => {
        if (bothSwitch.checked) {
            emailSwitch.checked = true;
            smsSwitch.checked = true;
            noneSwitch.checked = false;
        }
    });

    // None change
    noneSwitch.addEventListener('change', () => {
        if (noneSwitch.checked) {
            emailSwitch.checked = false;
            smsSwitch.checked = false;
            bothSwitch.checked = false;
        }
    });

    // Form submission: set hidden inputs
    const form = document.querySelector('form');
    form.addEventListener('submit', () => {
        inputEmail.value = emailSwitch.checked ? 1 : 0;
        inputSms.value = smsSwitch.checked ? 1 : 0;
    });

    // Basic validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(f => {
        f.addEventListener('submit', event => {
            if (!f.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            f.classList.add('was-validated');
        });
    });
})();
</script>
@endsection