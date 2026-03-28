@props(['value'])

<div class="border rounded-xl overflow-hidden bg-white">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-zinc-50 border-b">
                <tr>
                    @foreach(array_keys($value[0] ?? []) as $key)
                        <th class="px-4 py-2 font-semibold text-zinc-500 uppercase text-[10px]">{{ $key }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @foreach($value as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td class="px-4 py-2 text-zinc-600">{{ is_array($cell) ? '...' : $cell }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>