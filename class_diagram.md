@startuml Class_Diagram_StarUML

' =====================================================================
' 1. STARUML STYLE SKINPARAM (Mengatur Tampilan Klasik StarUML)
' =====================================================================
skinparam classAttributeIconSize 0

skinparam class {
    BackgroundColor White
    BorderColor Black
    FontColor Black
    FontName "Arial"
    FontSize 12
    
    AttributeFontName "Arial"
    AttributeFontSize 11
    AttributeFontColor Black
    
    MethodFontName "Arial"
    MethodFontSize 11
    MethodFontColor Black
    
    HeaderBackgroundColor White
    HeaderFontColor Black
    HeaderFontName "Arial"
    HeaderFontSize 12
    HeaderFontStyle bold
}

skinparam arrow {
    Color Black
    Thickness 1
}

skinparam shadow true

' =====================================================================
' 2. TITLE BOX (Kotak Hijau Judul StarUML)
' =====================================================================
legend top left
<back:#8BE28B><b>  Class Diagram - Sistem Informasi Sanggar Seni Goong Prasasti  </b></back>
endlegend

' =====================================================================
' 3. DEFINISI KELAS (Menerapkan +, -, #, dan ~)
' =====================================================================

class User {
    ' Atribut Protected (#) agar bisa diwarisi oleh Siswa & Staff
    #UserId: number
    #Username: string
    #EmailAddress: string
    #PasswordHash: string
    #Role: string
    #Status: string
    --
    +Login()
    +Logout()
    +UpdateProfile()
    ' Metode Package (~) untuk internal helper otentikasi
    ~ValidateToken()
}

class Siswa {
    ' Atribut Private (-) untuk enkapsulasi lokal
    -ProgramId: number
    -GoogleId: string
    --
    +DownloadQR()
    +PayIuran()
    +ViewJadwal()
}

class Staff {
    #GoogleId: string
    --
    ' Kelas dasar untuk semua staff / pengurus sanggar
}

class Ketua {
    --
    +ManageStaff()
    +ManageSiswa()
    +ManageJadwal()
    +ViewLaporanKeuangan()
}

class Humas {
    --
    +ManageGaleri()
    +ManageBerita()
    +ManageProgram()
}

class Pendaftaran {
    -RegistrationId: number
    -ProgramId: number
    -CandidateName: string
    -BirthDate: date
    -PhoneNumber: string
    -Address: string
    -RegistrationStatus: string
    --
    +AddRegistration()
    +UpdateRegistration()
    +ApproveRegistration()
    +RejectRegistration()
}

class Pembayaran {
    -PaymentId: number
    -PaymentDate: date
    -PaymentMonth: string
    -PaymentAmount: float
    -PaymentType: string
    -PaymentMethod: string
    -PaymentStatus: string
    --
    +AddPayment()
    +VerifyPayment()
    +CancelPayment()
}

class ProgramKelas {
    -ProgramId: number
    -ProgramName: string
    -ProgramDescription: string
    -ProgramFee: float
    -ProgramStatus: string
    --
    +AddProgram()
    +UpdateProgram()
    +DeleteProgram()
}

class JadwalLatihan {
    -ScheduleId: number
    -TrainerId: number
    -Day: string
    -StartTime: time
    -EndTime: time
    -Location: string
    --
    +AddSchedule()
    +UpdateSchedule()
    +DeleteSchedule()
}

class Absensi {
    -AttendanceId: number
    -AttendanceTime: datetime
    -AttendanceStatus: string
    --
    +ScanQR()
    +AddManualAttendance()
    +ApproveAttendance()
}

class Galeri {
    -GaleriId: number
    -Judul: string
    -Foto: string
    -Keterangan: string
    -Tanggal: date
    --
    +AddGaleri()
    +UpdateGaleri()
    +DeleteGaleri()
}

class Berita {
    -InformasiId: number
    -Judul: string
    -Slug: string
    -Kategori: string
    -Konten: string
    -Foto: string
    -Tanggal: date
    --
    +AddBerita()
    +UpdateBerita()
    +DeleteBerita()
}

' =====================================================================
' 4. HUBUNGAN & KARDINALITAS (StarUML Style dengan Layouting Arah)
' =====================================================================

' Generalization (Pewarisan vertikal dari atas ke bawah)
User <|-- Siswa : Generalization
User <|-- Staff : Generalization
Staff <|-- Ketua : Generalization
Staff <|-- Humas : Generalization

' Mengatur posisi horizontal kelas utama agar sejajar dan tidak tumpang tindih
Siswa -[hidden]right- Staff
Ketua -[hidden]right- Humas

' Hubungan Kelas Pendaftaran di sebelah kanan User
User "1" -right- "1...*" Pendaftaran : Association

' Hubungan Siswa dengan Pembayaran dan Absensi (ke bawah)
Siswa "1" *-down- "1...*" Pembayaran : Composition
Siswa "1" -down- "1...*" Absensi : Association

' Hubungan Humas dengan Galeri dan Berita (ke kanan agar tidak menabrak kelas kiri)
Humas "1" -right- "1...*" Galeri : Association
Humas "1" -right- "1...*" Berita : Association

' Hubungan Asosiasi Kerja Ketua (horizontal ke kiri untuk User & Siswa, ke bawah untuk Jadwal)
Ketua "1" -left- "1...*" User : Association
Ketua "1" -left- "1...*" Siswa : Association
Ketua "1" -down- "1...*" JadwalLatihan : Association

' Hubungan Siswa dan Humas dengan ProgramKelas (horizontal)
Siswa "1...*" -right- "1" ProgramKelas : Association
Humas "1" -down- "1...*" ProgramKelas : Association

' Hubungan ProgramKelas dengan JadwalLatihan (Jadwal di kiri, Program di kanan)
ProgramKelas "1" *-left- "1...*" JadwalLatihan : Composition

@enduml
