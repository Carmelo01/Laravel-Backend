<x-mail::message>
# Hi {{ $user->fname }}

You are now verified by the admin. Please login with the link below.

<x-mail::button :url="'https://4kmyst.bjmpbaliwag.com/'">
Go to website
</x-mail::button>

Thanks,<br>
MYST
</x-mail::message>
