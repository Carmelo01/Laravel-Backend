<x-mail::message>
# Hi {{ $user->fname }}

Sorry to inform you, that your registration has been declined by the admin due to some reasons.

{{ $comment }} - Admin

<x-mail::button :url="'https://4kmyst.bjmpbaliwag.com/faculty/register'">
Try again?
</x-mail::button>

Thanks,<br>
MYST
</x-mail::message>
