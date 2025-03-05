import sys
import pandas as pd
import numpy as np
from pmdarima import auto_arima

# Ambil data dari input (format: CSV dengan kolom 'date' dan 'amount')
data = pd.read_csv(sys.argv[1])

# Konversi kolom 'date' ke datetime dan set sebagai index
data['date'] = pd.to_datetime(data['date'])
data.set_index('date', inplace=True)

# Lakukan forecasting ARIMA
model = auto_arima(data['amount'], seasonal=False, trace=True)
forecast, conf_int = model.predict(n_periods=int(sys.argv[2]), return_conf_int=True)

# Output hasil forecasting
forecast_dates = pd.date_range(data.index[-1], periods=int(sys.argv[2]) + 1, freq='M')[1:]
forecast_df = pd.DataFrame({'date': forecast_dates, 'forecast': forecast})

# Simpan hasil ke CSV
forecast_df.to_csv(sys.argv[3], index=False)