<!DOCTYPE html>
<html>
<head>
    <title>IoT Project Monitoring</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <h2>IoT Project Monitoring</h2>

    <button onclick="deleteSelected()" class="delete-btn">Delete Selected</button>
    <input type="checkbox" id="selectAll" onclick="toggleSelectAll()"> <label for="selectAll">Select All</label>
    <br><br>

    <table id="dataTable">
        <tr>
            <th>Select</th>
            <th>ID</th>
            <th>Temperature (C)</th>
            <th>Timestamp</th>
        </tr>
    </table>

    <script>
        // Fungsi untuk memperbarui data tabel dengan data terbaru dari server
        function updateTable(data) {
            $("#dataTable").empty(); // Mengosongkan tabel sebelum menambahkan data baru
            $("#dataTable").append("<tr><th>Select</th><th>ID</th><th>Temperature (C)</th><th>Timestamp</th></tr>");
            for (var i = 0; i < data.length; i++) {
                var row = "<tr><td><input type='checkbox' name='selected[]' value='" + data[i].id + "'></td><td>" + data[i].id + "</td><td>" + data[i].temperature + "</td><td>" + data[i].timestamp + "</td></tr>";
                $("#dataTable").append(row);
            }
        }

        // Fungsi untuk memuat data suhu dari server secara berkala
        function loadTemperatureData() {
            $.ajax({
                url: "temperature.php", // URL script PHP yang akan mengembalikan data suhu dari server
                dataType: "json",
                success: function(data) {
                    updateTable(data); // Memperbarui tabel dengan data suhu terbaru
                },
                error: function(xhr, status, error) {
                    console.error("Error loading temperature data: " + error); // Menampilkan pesan kesalahan jika terjadi
                }
            });
        }

        // Fungsi untuk menghapus data terpilih
        function deleteSelected() {
            var selected = $("input[name='selected[]']:checked").map(function(){
                return $(this).val();
            }).get();
            
            $.ajax({
                url: "delete.php",
                method: "POST",
                data: { selected: selected },
                success: function() {
                    loadTemperatureData(); // Memuat ulang data setelah penghapusan berhasil
                },
                error: function(xhr, status, error) {
                    console.error("Error deleting data: " + error); // Menampilkan pesan kesalahan jika terjadi
                }
            });
        }

        // Fungsi untuk membalikkan status checkbox Select All
        function toggleSelectAll() {
            var checkboxes = document.getElementsByName('selected[]');
            var selectAllCheckbox = document.getElementById('selectAll');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = selectAllCheckbox.checked;
            }
        }

        // Memanggil fungsi untuk memuat data suhu saat halaman dimuat
        $(document).ready(function() {
            loadTemperatureData();
            // Mengatur interval untuk memperbarui data suhu setiap 5 detik (5000 milidetik)
            setInterval(loadTemperatureData, 5000);
        });
    </script>
</body>
</html>
