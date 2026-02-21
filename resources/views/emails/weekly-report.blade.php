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
                                <tr><td align="center"><div style="width:56px;height:56px;background:rgba(255,255,255,0.15);border-radius:14px;display:inline-block;line-height:56px;font-size:28px;">🎓</div></td></tr>
                                <tr><td align="center" style="padding-top:12px;">
                                    <h1 style="color:#ffffff;font-size:22px;font-weight:700;margin:0;letter-spacing:0.5px;">OFPPT Attendance</h1>
                                    <p style="color:rgba(255,255,255,0.75);font-size:13px;margin:4px 0 0;">Système de Gestion des Absences</p>
                                </td></tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Banner -->
                    <tr>
                        <td style="padding:0 40px;">
                            <div style="background:linear-gradient(135deg,#ede9fe,#ddd6fe);border-left:4px solid #8b5cf6;border-radius:0 8px 8px 0;padding:16px 20px;margin-top:28px;">
                                <table cellpadding="0" cellspacing="0" width="100%"><tr>
                                    <td width="36" style="vertical-align:top;"><span style="font-size:22px;">📊</span></td>
                                    <td>
                                        <p style="color:#5b21b6;font-size:14px;font-weight:700;margin:0;">Rapport Hebdomadaire des Absences</p>
                                        <p style="color:#6d28d9;font-size:13px;margin:4px 0 0;">Semaine du {{ $weekStart }} au {{ $weekEnd }}</p>
                                    </td>
                                </tr></table>
                            </div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:24px 40px 0;">
                            <p style="color:#1e293b;font-size:15px;line-height:1.6;margin:0;">Bonjour <strong style="color:#1e3a5f;">{{ $teacherName }}</strong>,</p>
                            <p style="color:#475569;font-size:14px;line-height:1.6;margin:12px 0 0;">Voici le résumé des absences de vos groupes pour la semaine écoulée :</p>
                        </td>
                    </tr>

                    <!-- Stats Row -->
                    <tr>
                        <td style="padding:20px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="33%" style="padding:0 4px 0 0;">
                                        <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1px solid #bfdbfe;border-radius:12px;padding:18px 12px;text-align:center;">
                                            <p style="color:#2563eb;font-size:28px;font-weight:800;margin:0;">{{ $totalAbsences }}</p>
                                            <p style="color:#1e40af;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin:4px 0 0;">Absences</p>
                                        </div>
                                    </td>
                                    <td width="33%" style="padding:0 4px;">
                                        <div style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:1px solid #a7f3d0;border-radius:12px;padding:18px 12px;text-align:center;">
                                            <p style="color:#059669;font-size:28px;font-weight:800;margin:0;">{{ $justifiedCount }}</p>
                                            <p style="color:#065f46;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin:4px 0 0;">Justifiées</p>
                                        </div>
                                    </td>
                                    <td width="33%" style="padding:0 0 0 4px;">
                                        <div style="background:linear-gradient(135deg,#fef2f2,#fee2e2);border:1px solid #fecaca;border-radius:12px;padding:18px 12px;text-align:center;">
                                            <p style="color:#dc2626;font-size:28px;font-weight:800;margin:0;">{{ $unjustifiedCount }}</p>
                                            <p style="color:#991b1b;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin:4px 0 0;">Non Justifiées</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Group Breakdown -->
                    @foreach($groups as $group)
                    <tr>
                        <td style="padding:0 40px 12px;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                                <tr>
                                    <td style="background:#f1f5f9;padding:12px 20px;border-bottom:1px solid #e2e8f0;">
                                        <table width="100%" cellpadding="0" cellspacing="0"><tr>
                                            <td><p style="color:#334155;font-size:14px;font-weight:700;margin:0;">🏷️ {{ $group['name'] }}</p></td>
                                            <td align="right"><span style="background:#1e3a5f;color:#fff;font-size:12px;font-weight:600;padding:4px 12px;border-radius:20px;">{{ $group['count'] }} absence(s)</span></td>
                                        </tr></table>
                                    </td>
                                </tr>
                                @if(count($group['students']) > 0)
                                @foreach($group['students'] as $student)
                                <tr>
                                    <td style="padding:12px 20px;{{ !$loop->last ? 'border-bottom:1px solid #e2e8f0;' : '' }}">
                                        <table width="100%" cellpadding="0" cellspacing="0"><tr>
                                            <td>
                                                <p style="color:#1e293b;font-size:14px;font-weight:500;margin:0;">{{ $student['name'] }}</p>
                                                <p style="color:#94a3b8;font-size:12px;margin:2px 0 0;">{{ $student['absences'] }} absence(s) — {{ $student['hours'] }}h manquées</p>
                                            </td>
                                            <td align="right">
                                                @if($student['absences'] >= 3)
                                                <span style="background:#fef2f2;color:#dc2626;font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;">⚠️ Critique</span>
                                                @else
                                                <span style="background:#fff7ed;color:#ea580c;font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;">Attention</span>
                                                @endif
                                            </td>
                                        </tr></table>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td style="padding:16px 20px;text-align:center;">
                                        <p style="color:#94a3b8;font-size:13px;margin:0;">✅ Aucune absence cette semaine</p>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                    @endforeach

                    <!-- CTA Button -->
                    <tr>
                        <td align="center" style="padding:20px 40px 28px;">
                            <a href="{{ $appUrl }}" style="display:inline-block;background:linear-gradient(135deg,#1e3a5f,#2d5f8a);color:#ffffff;font-size:15px;font-weight:600;text-decoration:none;padding:14px 36px;border-radius:10px;letter-spacing:0.3px;box-shadow:0 4px 14px rgba(30,58,95,0.3);">📊 Voir le tableau complet</a>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr><td style="padding:0 40px;"><hr style="border:none;border-top:1px solid #e2e8f0;margin:0;"></td></tr>
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
