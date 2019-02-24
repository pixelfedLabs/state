<?=
    /* Using an echo tag here so the `<? ... ?>` won't get parsed as short tags */
    '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<feed xml:lang="en-US" xmlns="http://www.w3.org/2005/Atom">
  <id>tag:{{parse_url(config('app.url'), PHP_URL_HOST)}},2005:/feeds/service/history</id>
  <link rel="alternate" type="text/html" href="{{config('app.url')}}"/>
  <link rel="self" type="application/atom+xml" href="{{route('feed.service.atom')}}"/>
  <title>{{config('app.name')}} - Incident History</title>
  <updated>{{now()->format(DATE_ATOM)}}</updated>
  <author>
    <name>{{config('app.name')}}</name>
  </author>
  @foreach($entries as $entry)
  <entry>
    <id>{{$entry->atomTag()}}</id>
    <published>{{$entry->created_at->format(DATE_ATOM)}}</published>
    <updated>{{$entry->updated_at->format(DATE_ATOM)}}</updated>
    <link rel="alternate" type="text/html" href="{{$entry->url()}}"/>
    <title>Incident on {{$entry->created_at->format('Y-m-d h:i:s')}} UTC</title>
    <content>{{$entry->title}}</content>
  </entry>
  @endforeach
</feed>