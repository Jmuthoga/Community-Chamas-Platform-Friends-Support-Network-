@extends('backend.master')

@section('title', 'Create Event')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fas fa-calendar-alt me-2"></i> Create Event
        </h3>
        <a href="{{ route('backend.admin.communications.events') }}" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Events
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('backend.admin.communications.events.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            {{-- Hidden inputs for database --}}
            <input type="hidden" name="send_email" id="input_send_email" value="0">
            <input type="hidden" name="send_sms" id="input_send_sms" value="0">

            {{-- Title --}}
            <div class="mb-4">
                <label for="title" class="form-label fw-bold">
                    Title <span class="text-danger">*</span>
                </label>
                <input type="text" id="title" name="title"
                       class="form-control form-control-lg"
                       value="{{ old('title') }}" required>
                <div class="invalid-feedback">Title is required.</div>
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <label for="description" class="form-label fw-bold">
                    Description <span class="text-danger">*</span>
                </label>
                <textarea id="description" name="description"
                          class="form-control form-control-lg"
                          rows="6" required>{{ old('description') }}</textarea>
                <div class="invalid-feedback">Description is required.</div>
            </div>

            {{-- Location --}}
            <div class="mb-4">
                <label for="location" class="form-label fw-bold">
                    Location
                </label>
                <input type="text" id="location" name="location"
                       class="form-control form-control-lg"
                       value="{{ old('location') }}">
            </div>

            {{-- Date & Time --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="event_date" class="form-label fw-bold">
                        Event Date <span class="text-danger">*</span>
                    </label>
                    <input type="date" id="event_date" name="event_date"
                           class="form-control form-control-lg"
                           value="{{ old('event_date') }}" required>
                    <div class="invalid-feedback">Event date is required.</div>
                </div>

                <div class="col-md-6">
                    <label for="event_time" class="form-label fw-bold">
                        Event Time
                    </label>
                    <input type="time" id="event_time" name="event_time"
                           class="form-control form-control-lg"
                           value="{{ old('event_time') }}">
                </div>
            </div>

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
                <div class="form-text mt-2">
                    Select Email, SMS, Both, or None. None is selected by default.
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-lg bg-gradient-primary px-5">
                    <i class="fas fa-save me-2"></i> Save Event
                </button>
            </div>

        </form>
    </div>
</div>

<script>
(() => {
    'use strict';

    const emailSwitch = document.getElementById('notify_email');
    const smsSwitch = document.getElementById('notify_sms');
    const bothSwitch = document.getElementById('notify_both');
    const noneSwitch = document.getElementById('notify_none');

    const inputEmail = document.getElementById('input_send_email');
    const inputSms = document.getElementById('input_send_sms');

    const updateBoth = () => {
        bothSwitch.checked = emailSwitch.checked && smsSwitch.checked;
    };

    const updateNone = () => {
        noneSwitch.checked = !emailSwitch.checked && !smsSwitch.checked;
    };

    [emailSwitch, smsSwitch].forEach(s => {
        s.addEventListener('change', () => {
            updateBoth();
            updateNone();
        });
    });

    bothSwitch.addEventListener('change', () => {
        if (bothSwitch.checked) {
            emailSwitch.checked = true;
            smsSwitch.checked = true;
            noneSwitch.checked = false;
        }
    });

    noneSwitch.addEventListener('change', () => {
        if (noneSwitch.checked) {
            emailSwitch.checked = false;
            smsSwitch.checked = false;
            bothSwitch.checked = false;
        }
    });

    const form = document.querySelector('form');
    form.addEventListener('submit', () => {
        inputEmail.value = emailSwitch.checked ? 1 : 0;
        inputSms.value = smsSwitch.checked ? 1 : 0;
    });

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