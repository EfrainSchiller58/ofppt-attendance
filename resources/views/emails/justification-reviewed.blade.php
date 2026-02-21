<x-mail::message>
# 📋 Justification {{ $decision === 'approved' ? 'approuvée ✅' : 'rejetée ❌' }}

Bonjour **{{ $studentName }}**,

Votre demande de justification a été **{{ $decision === 'approved' ? 'approuvée' : 'rejetée' }}** par l'administration.

| Détail | Information |
|--------|-------------|
| **Absence du** | {{ $date }} |
| **Matière** | {{ $subject }} |
| **Motif soumis** | {{ $reason }} |
| **Décision** | {{ $decision === 'approved' ? 'Approuvée' : 'Rejetée' }} |
@if($reviewNote)
| **Note de l'admin** | {{ $reviewNote }} |
@endif

@if($decision === 'approved')
Votre absence a été marquée comme justifiée. Aucune action supplémentaire n'est requise.
@else
Vous pouvez soumettre une nouvelle demande avec des documents complémentaires si nécessaire.
@endif

<x-mail::button :url="$appUrl">
Voir mes justifications
</x-mail::button>

Cordialement,<br>
**OFPPT Attendance**
</x-mail::message>
