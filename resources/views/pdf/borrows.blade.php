<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Borrow List</h2>
    <table>
        <thead>
            <tr>
                <th>Peminjam</th>
                <th>Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrows as $borrow)
            <tr>
                <td>{{ optional($borrow->user)->name }}</td>
                <td>{{ optional($borrow->user)->username }}</td>
                <td>{{ $borrow->tanggal_peminjaman }}</td>
                <td>{{ $borrow->tanggal_pengembalian }}</td>
                <td>{{ $borrow->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
