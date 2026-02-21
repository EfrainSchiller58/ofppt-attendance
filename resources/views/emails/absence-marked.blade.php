<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OFPPT Attendance</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f4f8;font-family:'Segoe UI',Roboto,Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0f4f8;padding:30px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#1e3a5f 0%,#2d5f8a 50%,#3b82c4 100%);padding:32px 40px;text-align:center;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <div style="width:56px;height:56px;background:rgba(255,255,255,0.15);border-radius:14px;display:inline-block;line-height:56px;font-size:28px;">🎓</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top:12px;">
                                        <h1 style="color:#ffffff;font-size:22px;font-weight:700;margin:0;letter-spacing:0.5px;">OFPPT Attendance</h1>
                                        <p style="color:rgba(255,255,255,0.75);font-size:13px;margin:4px 0 0;">Système de Gestion des Absences</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Alert Banner -->
                    <tr>
                        <td style="padding:0 40px;">
                            <div style="background:linear-gradient(135deg,#fef3c7,#fde68a);border-left:4px solid #f59e0b;border-radius:0 8px 8px 0;padding:16px 20px;margin-top:28px;">
                                <table cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td width="36" style="vertical-align:top;"><span style="font-size:22px;">⚠️</span></td>
                                        <td>
                                            <p style="color:#92400e;font-size:14px;font-weight:700;margin:0;">Nouvelle Absence Enregistrée</p>
                                            <p style="color:#a16207;font-size:13px;margin:4px 0 0;">Une absence a été enregistrée à votre nom</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:24px 40px 0;">
                            <p style="color:#1e293b;font-size:15px;line-height:1.6;margin:0;">Bonjour <strong style="color:#1e3a5f;">{{ $studentName }}</strong>,</p>
                            <p style="color:#475569;font-size:14px;line-height:1.6;margin:12px 0 0;">Votre formateur a enregistré une absence pour la séance suivante :</p>
                        </td>
                    </tr>

                    <!-- Details Card -->
                    <tr>
                        <td style="padding:20px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                                <tr>
                                    <td style="padding:16px 20px;border-bottom:1px solid #e2e8f0;">
                                        <table width="100%" cellpadding="0" cellspacing="0"><tr>
                                            <td width="32"><span style="font-size:18px;">📅</span></td>
                                            <td>
                                                <p style="color:#64748b;font-size:11px;text-transform:uppercase;letter-spacing:1px;margin:0;font-weight:600;">Date</p>
                                                <p style="color:#1e293b;font-size:15px;font-weight:600;margin:2px 0 0;">{{ $date }}</p>
                                            </td>
                                        </tr></table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 20px;border-bottom:1px solid #e2e8f0;">
                                        <table width="100%" cellpadding="0" cellspacing="0"><tr>
                                            <td width="32"><span style="font-size:18px;">🕐</span></td>
                                            <td>
                                                <p style="color:#64748b;font-size:11px;text-transform:uppercase;letter-spacing:1px;margin:0;font-weight:600;">Horaire</p>
                                                <p style="color:#1e293b;font-size:15px;font-weight:600;margin:2px 0 0;">{{ $startTime }} — {{ $endTime }}</p>
                                            </td>
                                        </tr></table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 20px;border-bottom:1px solid #e2e8f0;">
                                        <table width="100%" cellpadding="0" cellspacing="0"><tr>
                                            <td width="32"><span style="font-size:18px;">📚</span></td>
                                            <td>
                                                <p style="color:#64748b;font-size:11px;text-transform:uppercase;letter-spacing:1px;margin:0;font-weight:600;">Matière</p>
                                                <p style="color:#1e293b;font-size:15px;font-weight:600;margin:2px 0 0;">{{ $subject }}</p>
                                            </td>
                                        </tr></table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0"><tr>
                                            <td width="32"><span style="font-size:18px;">👨‍🏫</span></td>
                                            <td>
                                                <p style="color:#64748b;font-size:11px;text-transform:uppercase;letter-spacing:1px;margin:0;font-weight:600;">Formateur</p>
                                                <p style="color:#1e293b;font-size:15px;font-weight:600;margin:2px 0 0;">{{ $teacherName }}</p>
                                            </td>
                                        </tr></table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Tip Box -->
                    <tr>
                        <td style="padding:0 40px;">
                            <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:16px 20px;">
                                <p style="color:#1e40af;font-size:13px;margin:0;line-height:1.5;">💡 <strong>Conseil :</strong> Si cette absence est justifiée, vous pouvez soumettre un justificatif (certificat médical, convocation...) directement depuis l'application.</p>
                            </div>
                        </td>
                    </tr>

                    <!-- CTA Button -->
                    <tr>
                        <td align="center" style="padding:28px 40px;">
                            <a href="{{ $appUrl }}" style="display:inline-block;background:linear-gradient(135deg,#1e3a5f,#2d5f8a);color:#ffffff;font-size:15px;font-weight:600;text-decoration:none;padding:14px 36px;border-radius:10px;letter-spacing:0.3px;box-shadow:0 4px 14px rgba(30,58,95,0.3);">📋 Voir mes absences</a>
                        </td>
                    </tr>

                    <!-- Divider + Footer -->
                    <tr>
                        <td style="padding:0 40px;"><hr style="border:none;border-top:1px solid #e2e8f0;margin:0;"></td>
                    </tr>
                    <tr>
                        <td style="padding:24px 40px 32px;text-align:center;">
                            <p style="color:#94a3b8;font-size:12px;margin:0;">🎓 OFPPT Attendance — Système de Gestion des Absences</p>
                            <p style="color:#cbd5e1;font-size:11px;margin:8px 0 0;">Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
