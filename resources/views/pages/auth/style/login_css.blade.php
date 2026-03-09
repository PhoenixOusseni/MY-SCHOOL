<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', 'Segoe UI', sans-serif;
        height: 100vh;
        margin: 0;
        overflow: hidden;
        background: #0f0f0f;
    }

    .login-container {
        height: 100vh;
        display: flex;
    }

    /* ===== LEFT PANEL (FORM) ===== */
    .login-form-section {
        width: 45%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        padding: 40px 30px;
        position: relative;
        z-index: 1;
    }

    .login-form-section::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 4px;
        background: linear-gradient(90deg, #c41e3a, #ff6b6b, #c41e3a);
        background-size: 200% 100%;
        animation: shimmer 3s linear infinite;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .login-form-wrapper {
        width: 100%;
        max-width: 420px;
    }

    /* ===== LOGO ===== */
    .logo-section {
        text-align: center;
        margin-bottom: 36px;
    }

    .logo-icon {
        width: 72px;
        height: 72px;
        background: linear-gradient(135deg, #c41e3a 0%, #8b1a2e 100%);
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        box-shadow: 0 8px 32px rgba(196, 30, 58, 0.35);
        position: relative;
        animation: floatLogo 3s ease-in-out infinite;
    }

    @keyframes floatLogo {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }

    .logo-icon::after {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 20px;
        background: linear-gradient(135deg, #c41e3a, #ff6b6b, #8b1a2e);
        z-index: -1;
        opacity: 0.4;
        filter: blur(8px);
    }

    .logo-icon i {
        font-size: 34px;
        color: white;
    }

    .logo-text {
        font-size: 22px;
        font-weight: 800;
        color: #1a1a2e;
        letter-spacing: -0.5px;
        margin-bottom: 4px;
    }

    .logo-subtext {
        color: #9ca3af;
        font-size: 13px;
        font-weight: 400;
    }

    /* ===== FORM FIELDS ===== */
    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
        font-size: 13px;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .form-control {
        padding: 13px 16px;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        background: #f9fafb;
        transition: all 0.25s ease;
        color: #1f2937;
    }

    .form-control:focus {
        border-color: #c41e3a;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.08);
        outline: none;
    }

    .form-control::placeholder {
        color: #d1d5db;
        font-weight: 300;
    }

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon-wrapper .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 16px;
        transition: color 0.25s ease;
        pointer-events: none;
    }

    .input-icon-wrapper .form-control {
        padding-left: 42px;
    }

    .input-icon-wrapper .form-control:focus + .input-icon,
    .input-icon-wrapper:focus-within .input-icon {
        color: #c41e3a;
    }

    .password-toggle {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #9ca3af;
        font-size: 16px;
        transition: color 0.25s ease;
        z-index: 2;
    }

    .password-toggle:hover {
        color: #c41e3a;
    }

    /* ===== BUTTON ===== */
    .btn-login {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #c41e3a 0%, #8b1a2e 100%);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 700;
        font-size: 15px;
        font-family: 'Inter', sans-serif;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
        margin-top: 6px;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .btn-login::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        transition: left 0.5s ease;
    }

    .btn-login:hover::before {
        left: 100%;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(196, 30, 58, 0.4);
        background: linear-gradient(135deg, #d4213f 0%, #9b1e34 100%);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    /* ===== EXTRAS ===== */
    .forgot-password {
        color: #c41e3a;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .forgot-password:hover {
        color: #8b1a2e;
        text-decoration: underline;
    }

    .form-check-input:checked {
        background-color: #c41e3a;
        border-color: #c41e3a;
    }

    .form-check-label {
        font-size: 13px;
        color: #6b7280;
    }

    .divider {
        text-align: center;
        margin: 20px 0 16px;
        position: relative;
    }

    .divider::before {
        content: '';
        position: absolute;
        left: 0; top: 50%;
        width: 100%;
        height: 1px;
        background: #e5e7eb;
    }

    .divider span {
        background: white;
        padding: 0 14px;
        position: relative;
        color: #9ca3af;
        font-size: 12px;
        font-weight: 500;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .register-link {
        text-align: center;
        color: #6b7280;
        font-size: 13px;
    }

    .register-link a {
        color: #c41e3a;
        text-decoration: none;
        font-weight: 700;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

    /* ===== ALERTS ===== */
    .alert {
        border-radius: 10px;
        border: none;
        padding: 12px 16px;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border-left: 3px solid #c41e3a;
    }

    .alert-success {
        background: #f0fdf4;
        color: #14532d;
        border-left: 3px solid #16a34a;
    }

    /* ===== RIGHT PANEL ===== */
    .register-info-section {
        flex: 1;
        background: linear-gradient(145deg, #1a0a0f 0%, #2d0f1a 40%, #c41e3a 100%);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding: 50px 55px;
        position: relative;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .register-info-section::-webkit-scrollbar {
        width: 6px;
    }

    .register-info-section::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    .register-info-section::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.25);
        border-radius: 10px;
    }

    .register-info-section::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.4);
    }

    /* Animated background blobs */
    .register-info-section::before {
        content: '';
        position: absolute;
        top: -20%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(196, 30, 58, 0.5) 0%, transparent 65%);
        animation: blob1 8s ease-in-out infinite alternate;
    }

    .register-info-section::after {
        content: '';
        position: absolute;
        bottom: -15%;
        left: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255, 107, 107, 0.25) 0%, transparent 65%);
        animation: blob2 10s ease-in-out infinite alternate;
    }

    @keyframes blob1 {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(-30px, 30px) scale(1.1); }
    }

    @keyframes blob2 {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(30px, -30px) scale(1.15); }
    }

    .info-content {
        position: relative;
        z-index: 2;
    }

    /* Badge */
    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 7px 18px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 24px;
        backdrop-filter: blur(10px);
        letter-spacing: 0.5px;
    }

    .info-badge .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #4ade80;
        animation: pulseDot 2s ease-in-out infinite;
    }

    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.4); }
    }

    .info-title {
        font-size: 34px;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 14px;
        letter-spacing: -0.5px;
    }

    .info-highlight {
        color: #fbbf24;
        position: relative;
    }

    .info-subtitle {
        font-size: 15px;
        opacity: 0.75;
        margin-bottom: 40px;
        line-height: 1.6;
    }

    /* Stats row */
    .stats-row {
        display: flex;
        gap: 24px;
        margin-bottom: 40px;
    }

    .stat-item {
        flex: 1;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 14px;
        padding: 18px 16px;
        text-align: center;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        background: rgba(255, 255, 255, 0.14);
        transform: translateY(-4px);
    }

    .stat-number {
        font-size: 26px;
        font-weight: 800;
        color: #fbbf24;
        letter-spacing: -1px;
    }

    .stat-label {
        font-size: 11px;
        opacity: 0.7;
        margin-top: 4px;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    /* Feature cards */
    .feature-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
        margin-bottom: 36px;
    }

    .feature-card {
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 18px;
        border-radius: 14px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        cursor: default;
    }

    .feature-card:hover {
        background: rgba(255, 255, 255, 0.13);
        border-color: rgba(255, 255, 255, 0.2);
        transform: translateY(-4px);
    }

    .feature-icon {
        width: 42px;
        height: 42px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
    }

    .feature-icon i {
        font-size: 20px;
    }

    .feature-title {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .feature-desc {
        font-size: 11px;
        opacity: 0.7;
        line-height: 1.5;
    }

    /* Benefit list */
    .benefits-section {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 28px;
    }

    .benefits-title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 16px;
        opacity: 0.9;
    }

    .benefit-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .benefit-item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 12.5px;
        opacity: 0.85;
        line-height: 1.4;
    }

    .benefit-item i {
        font-size: 14px;
        color: #4ade80;
        flex-shrink: 0;
        margin-top: 1px;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .login-form-section {
            width: 100%;
        }

        .register-info-section {
            display: none;
        }

        body {
            overflow: auto;
        }

        .login-form-section {
            height: 100vh;
        }
    }

    @media (max-width: 576px) {
        .login-form-section {
            padding: 30px 20px;
        }
    }
</style>
