# PHP Work Hours Register

## Opis projektu

Jest to aplikacja webowa stworzona w Symfony 5.10.12, umożliwiająca zarządzanie czasem pracy pracowników. System pozwala na rejestrowanie czasu pracy, obliczanie wynagrodzenia oraz podsumowywanie przepracowanych godzin w ujęciu dziennym i miesięcznym.

## Technologie
- **PHP** (8.3.11)
- **Symfony** (5.10.12)
- **MySQL** (8.0)
- **Docker Desktop** (do łatwego zarządzania środowiskiem)
- **Postman** (do testowania połączenia między kontenerami oraz poprawności implementacji)

## Instalacja

1. Sklonuj repozytorium:
   ```bash
   git clone https://github.com/workHoursRegister.git
   cd workHoursRegister
   ```

2. Uruchom kontenery Dockera:
   ```bash
   docker-compose up -d --build
   ```

3. Wykonaj migracje bazy danych:
   ```bash
   docker-compose exec app php artisan migrate
   ```

4. (Opcjonalnie) Wypełnij bazę testowymi danymi:
   ```bash
   docker-compose exec app php artisan db:seed
   ```

5. Aplikacja powinna być dostępna pod adresem:
   ```
   http://localhost:8000
   ```

## Struktura danych

### Encja `Employee`
| Pole       | Typ    | Opis                       |
|------------|--------|----------------------------|
| `uuid`     | UUID   | Unikalny identyfikator     |
| `fullname` | string | Imię i nazwisko pracownika |

### Encja `WorkTime`
| Pole          | Typ      | Opis                       |
|---------------|--------- |----------------------------|
| `id`          | int      | Unikalny identyfikator     |
| `employee`    | Employee | Relacja z encją Employee   |
| `timeStart`   | datetime | Data i godzina rozpoczęcia |
| `timeEnd`     | datetime | Data i godzina zakończenia |
| `dateStart`   | date     | Dzień rozpoczęcia          |
| `timeMinutes` | int      | Czas pracy w minutach      |

## Endpointy API

### 1. Tworzenie pracownika
**POST** `/api/employee`
#### Przykładowe żądanie:
```json
{
  "fullname": "Karol Szabat"
}
```
#### Przykładowa odpowiedź:
```json
{
  "response": {
    "id": "unikalny identyfikator"
  }
}
```

### 2. Rejestracja czasu pracy
**POST** `/api/employee/{id}/worktime`
- **`id`** - unikalny identyfikator pracownika
#### Przykładowe żądanie:
```json
{
  "start": "2024-03-21 08:00",
  "end": "2024-03-21 14:00"
}
```
#### Przykładowa odpowiedź:
```json
{
  "response": "Czas pracy został dodany!"
}
```

### 3. Podsumowanie czasu pracy
#### a) Podsumowanie dzienne
**GET** `/api/employee/{id}/worktime`
- **`id`** - unikalny identyfikator pracownika
#### Przykładowe żądanie:
```json
{
  "date": "01.01.2000"
}
```
#### Przykładowa odpowiedź:
```json
{
  "response": {
    "total": "120 PLN",
    "standardHours": 6,
    "standardRate": "20 PLN"
  }
}
```

#### b) Podsumowanie miesięczne
**GET** `/api/employee/{id}/worktime`
- **`id`** - unikalny identyfikator pracownika
- #### Przykładowe żądanie:
```json
{
  "date": "01.2000"
}
```
#### Przykładowa odpowiedź:
```json
{
  "response": {
    "standardHours": 40,
    "standardRate": "20 PLN",
    "overtimeHours": 8,
    "overtimeRate": "40 PLN",
    "total": "1120 PLN"
  }
}
```

## Testowanie API

Aby przetestować API, można użyć narzędzia **Postman** lub **cURL**. Przykładowe polecenie:
```bash
curl -X POST http://localhost:8000/api/employee \
  -H "Content-Type: application/json" \
  -d '{"fullname": "Karol Szabat"}'
```

## Autor
- Patrycja Tkaczuk
- pati.tkaczuk@gmail.com
- 03.2025

## Licencja
Projekt stworzony na potrzeby rekrutacji i pozostaje własnością autora.

