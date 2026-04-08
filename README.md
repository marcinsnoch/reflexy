# Reflexy

Wtyczka WordPress do publikowania Reflexów (refleksji, przemyśleń) dla strony Typex.info.

## Opis

Reflexy to zaawansowana wtyczka WordPress umożliwiająca tworzenie i zarządzanie treściami typu "refleksje". Wtyczka oferuje:

- **Typ postu niestandardowego**: Dedykowany typ postu "reflexy" z pełną obsługą
- **Szablony**: Automatyczne ładowanie własnych szablonów dla archiwum i pojedynczych postów
- **Shortcode**: `[show_reflexy]` do wyświetlania refleksji na dowolnej stronie
- **Kategorie**: Taksonomia "reflexy_category" do organizowania treści
- **Ustawienia**: Panel administracyjny do konfiguracji (ilość postów, klasy CSS)
- **Responsywność**: Pełna obsługa urządzeń mobilnych
- **Aktualizacje**: Automatyczne sprawdzanie i instalacja nowych wersji

## Instalacja

1. Pobierz plik ZIP z [GitHub](https://github.com/marcinsnoch/reflexy)
2. W panelu WordPress przejdź do **Wtyczki > Dodaj nową**
3. Kliknij **Prześlij wtyczkę** i wybierz pobrany plik
4. Aktywuj wtyczkę

## Użycie

### Dodawanie Reflexów
1. Przejdź do **Wpisy > Reflexy > Dodaj nowy**
2. Wypełnij tytuł i treść refleksji
3. Dodaj obrazek wyróżniający (opcjonalnie)
4. Przypisz kategorię (opcjonalnie)
5. Opublikuj

### Wyświetlanie na stronie głównej
Dodaj shortcode na dowolnej stronie/stronie głównej:
```
[show_reflexy]
```

### Konfiguracja
Przejdź do **Wpisy > Reflexy > Ustawienia** aby skonfigurować:
- Klasę CSS dla sekcji
- Ilość postów w shortcode
- Ilość postów na stronie archiwum

### Strony
- **Archiwum**: `/reflexy/` - lista wszystkich refleksji
- **Pojedyncze**: Automatyczne URL dla każdego posta

## Wymagania

- WordPress 5.0+
- PHP 7.4+

## Licencja

MIT License - zobacz plik LICENSE

## Autor

Marcin Snoch - [https://gravatar.com/marcinmsxtech](https://gravatar.com/marcinmsxtech)
