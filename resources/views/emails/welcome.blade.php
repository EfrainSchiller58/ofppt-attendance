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
                        <td style="background:linear-gradient(135deg,#1e3a5f 0%,#2d5f8a 50%,#3b82c4 100%);padding:40px 40px 32px;text-align:center;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr><td align="center"><div style="width:64px;height:64px;background:rgba(255,255,255,0.15);border-radius:16px;display:inline-block;line-height:64px;font-size:32px;">🎓</div></td></tr>
                                <tr><td align="center" style="padding-top:16px;">
                                    <h1 style="color:#ffffff;font-size:26px;font-weight:700;margin:0;letter-spacing:0.5px;">Bienvenue sur OFPPT Attendance</h1>
                                    <p style="color:rgba(255,255,255,0.8);font-size:14px;margin:8px 0 0;">Votre compte a été créé avec succès</p>
                                </td></tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Welcome Banner -->
                    <tr>
                        <td style="padding:0 40px;">
                            <div style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);border-left:4px solid #10b981;border-radius:0 8px 8px 0;padding:16px 20px;margin-top:28px;">
                                <table cellpadding="0" cellspacing="0" width="100%"><tr>
                                    <td width="36" style="vertical-align:top;"><span style="font-size:22px;">🎉</span></td>
                                    <td>
                                        <p style="color:#065f46;font-size:14px;font-weight:700;margin:0;">Compte Activé</p>
                                        <p style="color:#047857;font-size:13px;margin:4px 0 0;">Vous pouvez maintenant accéder à la plateforme</p>
                                    </td>
                                </tr></table>
                            </div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:24px 40px 0;">
                            <p style="color:#1e293b;font-size:15px;line-height:1.6;margin:0;">Bonjour <strong style="color:#1e3a5f;">{{ $userName }}</strong>,</p>
                            <p style="color:#475569;font-size:14px;line-height:1.6;margin:12px 0 0;">Votre compte {{ $role === 'student' ? 'stagiaire' : ($role === 'teacher' ? 'formateur' : 'administrateur') }} a été créé sur la plateforme OFPPT Attendance. Voici vos identifiants de connexion :</p>
                        </td>
                    </tr>

                    <!-- Credentials Card -->
                    <tr>
                        <td style="padding:20px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:2px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                                <tr>
                                    <td style="padding:20px;border-bottom:1px solid #e2e8f0;">
                                        <table width="100%" cellpadding="0" cellspacing="0"><tr>
                                            <td width="32"><span style="font-size:18px;">📧</span></td>
                                            <td>
                                                <p style="color:#64748b;font-size:11px;text-transform:uppercase;letter-spacing:1px;margin:0;font-weight:600;">Email de Connexion</p>
                                                <p style="color:#1e3a5f;font-size:16px;font-weight:700;margin:4px 0 0;font-family:'Courier New',monospace;">{{ $email }}</p>
                                            </td>
                                        </tr></table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:20px;border-bottom:1px solid #e2e8f0;">
                                        <table width="100%" cellpadding="0" cellspacing="0"><tr>
                                            <td width="32"><span style="font-size:18px;">🔑</span></td>
                                            <td>
                                                <p style="color:#64748b;font-size:11px;text-transform:uppercase;letter-spacing:1px;margin:0;font-weight:600;">Mot de Passe Temporaire</p>
                                                <p style="color:#1e3a5f;font-size:16px;font-weight:700;margin:4px 0 0;font-family:'Courier New',monospace;">{{ $temporaryPassword }}</p>
                                            </td>
                                        </tr></table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0"><tr>
                                            <td width="32"><span style="font-size:18px;">👤</span></td>
                                            <td>
                                                <p style="color:#64748b;font-size:11px;text-transform:uppercase;letter-spacing:1px;margin:0;font-weight:600;">Rôle</p>
                                                <p style="color:#1e293b;font-size:15px;font-weight:600;margin:4px 0 0;">{{ $role === 'student' ? '🎓 Stagiaire' : ($role === 'teacher' ? '👨‍🏫 Formateur' : '⚙️ Administrateur') }}</p>
                                            </td>
                                        </tr></table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Security Notice -->
                    <tr>
                        <td style="padding:0 40px;">
                            <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:10px;padding:16px 20px;">
                                <p style="color:#92400e;font-size:13px;margin:0;line-height:1.5;">🔒 <strong>Sécurité :</strong> Lors de votre première connexion, vous serez invité à changer votre mot de passe. Choisissez un mot de passe fort et ne le partagez avec personne.</p>
                            </div>
                        </td>
                    </tr>

                    <!-- Features -->
                    <tr>
                        <td style="padding:24px 40px 0;">
                            <p style="color:#1e293b;font-size:14px;font-weight:600;margin:0 0 12px;">Ce que vous pouvez faire :</p>
                            <table width="100%" cellpadding="0" cellspacing="0">
                                @if($role === 'student')
                                <tr><td style="padding:6px 0;"><p style="color:#475569;font-size:13px;margin:0;">📋 Consulter vos absences en temps réel</p></td></tr>
                                <tr><td style="padding:6px 0;"><p style="color:#475569;font-size:13px;margin:0;">📎 Soumettre des justificatifs en ligne</p></td></tr>
                                <tr><td style="padding:6px 0;"><p style="color:#475569;font-size:13px;margin:0;">📊 Suivre vos statistiques de présence</p></td></tr>
                                <tr><td style="padding:6px 0;"><p style="color:#475569;font-size:13px;margin:0;">🔔 Recevoir des notifications importantes</p></td></tr>
                                @elseif($role === 'teacher')
                                <tr><td style="padding:6px 0;"><p style="color:#475569;font-size:13px;margin:0;">✅ Marquer les absences de vos stagiaires</p></td></tr>
                                <tr><td style="padding:6px 0;"><p style="color:#475569;font-size:13px;margin:0;">📊 Consulter les statistiques par groupe</p></td></tr>
                                <tr><td style="padding:6px 0;"><p style="color:#475569;font-size:13px;margin:0;">📋 Gérer vos groupes et séances</p></td></tr>
                                <tr><td style="padding:6px 0;"><p style="color:#475569;font-size:13px;margin:0;">📧 Rapports hebdomadaires automatiques</p></td></tr>
                                @else
                                <tr><td style="padding:6px 0;"><p style="color:#475569;font-size:13px;margin:0;">⚙️ Gérer les utilisateurs et les groupes</p></td></tr>
                                <tr><td style="padding:6px 0;"><p style="color:#475569;font-size:13px;margin:0;">📋 Examiner les justifications</p></td></tr>
                                <tr><td style="padding:6px 0;"><p style="color:#475569;font-size:13px;margin:0;">📊 Tableau de bord complet</p></td></tr>
                                <tr><td style="padding:6px 0;"><p style="color:#475569;font-size:13px;margin:0;">🔔 Notifications et alertes</p></td></tr>
                                @endif
                            </table>
                        </td>
                    </tr>

                    <!-- CTA Button -->
                    <tr>
                        <td align="center" style="padding:28px 40px;">
                            <a href="{{ $appUrl }}" style="display:inline-block;background:linear-gradient(135deg,#1e3a5f,#2d5f8a);color:#ffffff;font-size:15px;font-weight:600;text-decoration:none;padding:14px 36px;border-radius:10px;letter-spacing:0.3px;box-shadow:0 4px 14px rgba(30,58,95,0.3);">🚀 Se connecter maintenant</a>
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
