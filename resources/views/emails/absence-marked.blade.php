<x-mail::message>
# ⚠️ Nouvelle absence enregistrée

Bonjour **{{ $studentName }}**,

Une absence a été enregistrée à votre nom :

| Détail | Information |
|--------|-------------|
| **Date** | {{ $date }} |
| **Horaire** | {{ $startTime }} - {{ $endTime }} |
| **Matière** | {{ $subject }} |
| **Formateur** | {{ $teacherName }} |

Si cette absence est justifiée, vous pouvez soumettre un justificatif directement depuis l'application.

<x-mail::button :url="$appUrl">
Voir mes absences
</x-mail::button>

Cordialement,<br>
**OFPPT Attendance**
</x-mail::message>
