import csv
import os
from datetime import datetime, timedelta
import random

CSV_PATH = 'assets/data/energie.csv'

# Read all existing days in the CSV
def get_existing_days():
    days = set()
    with open(CSV_PATH, encoding='utf-8') as f:
        reader = csv.reader(f, delimiter=';')
        header = next(reader)
        tijd_idx = header.index('Tijdstip')
        for row in reader:
            if row and len(row) > tijd_idx:
                day = row[tijd_idx].split(' ')[0]
                days.add(day)
    return days

def generate_row(date, time):
    # Generate realistic, slightly varied values
    v = lambda base, var: f'{base + random.uniform(-var, var):.8f}'.replace('.', ',')
    tijdstip = f'{date} {time}'
    zonne_v = v(4.1, 0.2)
    zonne_a = v(0.12, 0.05)
    waterstof_prod = v(0.02, 0.01)
    stroomverbruik = v(0.75, 0.1)
    waterstof_auto = '0'
    buiten_temp = v(4.0, 2.0)
    binnen_temp = v(21.0, 1.0)
    luchtdruk = v(1012.0, 1.0)
    luchtvocht = v(70.0, 5.0)
    accuniveau = v(99.9, 0.5)
    co2 = v(495.0, 10.0)
    waterstof_woning = '0'
    waterstof_auto_opslag = v(70.0, 30.0)
    # Add extra semicolons to match your format (header has 14 columns, but rows have many extra semicolons)
    row = [tijdstip, zonne_v, zonne_a, waterstof_prod, stroomverbruik, waterstof_auto, buiten_temp, binnen_temp, luchtdruk, luchtvocht, accuniveau, co2, waterstof_woning, waterstof_auto_opslag]
    # Pad with semicolons to match your teacher's format (about 90 columns)
    while len(row) < 90:
        row.append('')
    return row

def main():
    existing_days = get_existing_days()
    # All days from 2023 to 2025
    start = datetime(2023, 1, 1)
    end = datetime(2025, 12, 31)
    all_days = [(start + timedelta(days=i)).strftime('%#d-%#m-%Y') for i in range((end-start).days+1)]
    missing_days = [d for d in all_days if d not in existing_days]
    if not missing_days:
        print('No missing days to add.')
        return
    print(f'Adding {len(missing_days)} days...')
    # Prepare times for 15-min intervals
    times = [f'{h:02}:{m:02}' for h in range(24) for m in range(0,60,15)]
    with open(CSV_PATH, 'a', encoding='utf-8', newline='') as f:
        writer = csv.writer(f, delimiter=';')
        for day in missing_days:
            for t in times:
                row = generate_row(day, t)
                writer.writerow(row)
    print('Done!')

if __name__ == '__main__':
    main() 