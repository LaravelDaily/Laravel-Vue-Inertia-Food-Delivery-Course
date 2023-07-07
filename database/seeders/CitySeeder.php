<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = ['Aalborg', 'Aarhus', 'Aba', 'Abeokuta', 'Abovyan', 'Abuja', 'Accra', 'Adana', 'Ado Ekiti', 'Aktobe', 'Akure', 'Almaty', 'Alytus', 'Amadora', 'Amsterdam', 'Ankara', 'Antwerp', 'Arusha', 'Asaba', 'Athens', 'Baku', 'Bălți', 'Banja Luka', 'Banska Bystrica', 'Barcelona', 'Barysaw', 'Basel', 'Batumi', 'Bauchi', 'Belfast', 'Belgrade', 'Bender (Tighina)', 'Benin City', 'Bergen', 'Berlin', 'Bern', 'Bialystok', 'Bijeljina', 'Bilbao', 'Birmingham', 'Braga', 'Brasov', 'Bratislava', 'Brno', 'Brussels', 'Bucharest', 'Budapest', 'Burgas', 'Bursa', 'Calabar', 'Cape Coast', 'Cape Town', 'Celje', 'Ceske Budejovice', 'Charleroi', 'Chișinău', 'Cluj-Napoca', 'Coimbra', 'Constanta', 'Copenhagen', 'Cork', 'Cornwall', 'Dar es Salaam', 'Daugavpils', 'Debrecen', 'Derry', 'Diani', 'Dnipro', 'Dodoma', 'Donetsk', 'Drammen', 'Dublin', 'Durban', 'East London', 'Eindhoven', 'Eldoret', 'Emalahleni', 'Embu', 'Enugu', 'Ermelo', 'Esbjerg', 'Faro', 'Frankfurt', 'Galati', 'Ganja', 'Garden Route', 'Gdansk', 'Gdynia', 'Geneva', 'Ghent', 'Gothenburg', 'Gozo', 'Grahamstown', 'Graz', 'Gyumri', 'Haapsalu', 'Hague', 'Hamburg', 'Helsinki', 'Hradec Kralove', 'Iasi', 'Ibadan', 'Ilorin', 'Innsbruck', 'Istanbul', 'İzmir', 'Jelgava', 'Johannesburg', 'Jonava', 'Jos', 'Jõhvi', 'Jurmala', 'Jyväskylä', 'Kaduna', 'Kakamega', 'Kampala', 'Kano', 'Karaganda', 'Karatina', 'Kaunas', 'Kharkiv', 'Kilifi', 'Kimberley', 'Kisumu', 'Kitale', 'Klaipeda', 'Kohtla-Järve', 'Köln', 'Koper', 'Kosice', 'Kragujevac', 'Krakow', 'Kranj', 'Kumasi', 'Kuressaare', 'Kyiv', 'Lagos', 'Lankaran', 'Larissa', 'Larnaca', 'Lausanne', 'Leeds', 'Leskovac', 'Liberec', 'Liège', 'Liepaja', 'Lille–Roubaix', 'Limassol', 'Limerick', 'Linz', 'Lisbon', 'Ljubljana', 'Lodz', 'London', 'Lublin', 'Lviv', 'Lyon ', 'Madrid', 'Makurdi', 'Maladzyechna', 'Málaga', 'Malindi', 'Malmo', 'Manchester', 'Maribor', 'Marijampole', 'Marseille', 'Mazeikiai', 'Mbombela', 'Meru', 'Milan', 'Mingachevir', 'Minsk', 'Miskolc', 'Mombasa', 'Mthatha', 'München', 'Mwanza', 'Nairobi', 'Naivasha', 'Nakuru', 'Nanyuki', 'Naples', 'Narva', 'Nicosia', 'Niš', 'Nitra', 'Novi Sad', 'Nur-Sultan', 'Nyeri', 'Odense', 'Odesa', 'Olomouc', 'Onitsha', 'Oradea', 'Osijek', 'Oslo', 'Ostrava', 'Oulu', 'Owerri', 'Palermo', 'Panevezys', 'Paphos', 'Pardubice', 'Paris', 'Parnu', 'Patras', 'Pécs', 'Phuthaditjhaba', 'Pietermaritzburg', 'Piraeus', 'Pitesti', 'Ploiesti', 'Plovdiv', 'Plzen', 'Polokwane', 'Poprad', 'Port Elizabeth', 'Port Harcourt', 'Porto', 'Potchefstroom', 'Poznan', 'Prague', 'Presov', 'Pretoria', 'Queenstown', 'Rakvere', 'Reykjavik', 'Rezekne', 'Rîbnița', 'Riga', 'Rijeka', 'Rome', 'Rotterdam', 'Ruse', 'Rustenburg', 'Salihorsk', 'Salzburg', 'Sarajevo', 'Setubal', 'Seville', 'Sheffield', 'Shymkent', 'Siauliai', 'Slavonski brod', 'Sofia', 'Sopot', 'Sousse', 'Split', 'Stavanger / Sandnes', 'Stockholm', 'Stuttgart', 'Sumgait', 'Szczecin', 'Szeged', 'Takoradi', 'Tallinn', 'Tampere', 'Tartu', 'Tbilisi', 'Thessaloniki', 'Thika', 'Thohoyandou', 'Timisoara', 'Tiraspol', 'Toulouse', 'Trencin', 'Trnava', 'Trondheim', 'Tunis', 'Turin', 'Turku', 'Tuzla', 'Upington', 'Uppsala', 'Usti nad Labem', 'Utrecht', 'Uyo', 'Vagharshapat', 'Valencia', 'Valletta', 'Valmiera', 'Vanadzor', 'Varna', 'Västerås', 'Ventspils', 'Vienna', 'Vila Nova de Gaia', 'Viljandi', 'Vilnius', 'Vinnytsya', 'Warri', 'Warsaw', 'Welkom', 'Wroclaw', 'Yerevan', 'Zadar', 'Zagreb', 'Zaporizhia', 'Zaporizhzhia', 'Zaria', 'Zenica', 'Zhodzina', 'Zilina', 'Zvolen', 'Zürich', 'Other'];

        foreach ($cities as $city) {
            City::create(['name' => $city]);
        }
    }
}
