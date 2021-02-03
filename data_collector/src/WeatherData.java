import java.io.IOException;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.ConcurrentModificationException;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import java.lang.Math;

public class WeatherData extends Thread {

    private DateTimeFormatter dtf;
    private LocalDateTime ldt;
    private int year;
    private int month;
    private int day;
    private int hour;
    private int minute;
    private int second;

    private SocketClient socket;

    private HashMap<Integer, WeatherStationWrapper> data;

    private Thread thread = null;

    public WeatherData(String host) {
        socket = new SocketClient(host, 7790);
        data = new HashMap<Integer, WeatherStationWrapper>();
        dtf = DateTimeFormatter.ofPattern("yyyy/MM/dd HH:mm:ss");
        GetCurrentTime();
    }

    public void start() {
        if (thread == null) {
            thread = new Thread(this);
            thread.start();
        }
    }

    public void run() {
        GetCurrentTime();
        while (true) {
            try {
                Thread.sleep(1000*(((59-minute)*60)+(60-second)));
                //Thread.sleep(60000);
                try {
                    socket.OpenSocket();
                    Iterator stations_it = data.entrySet().iterator();

                    // loop through the hashmap containing all data
                    while (stations_it.hasNext()) {
                        Map.Entry<Integer, WeatherStationWrapper> pair = (Map.Entry) stations_it.next();
                        WeatherStationWrapper station = pair.getValue();

                        // extract data from the weatherstation object
                        String nr = String.format("%06d", pair.getKey());
                        Float temperature = station.weatherStation.temperature;
                        Float dewpoint = station.weatherStation.dewPoint;
                        Float rainfall = station.weatherStation.rainfall;

                        // calculate the humidity with the temperature and dewpoint
                        // first calculate value A
                        // A = (17.625 * temperature) / (243.03 + temperature)
                        Double A = (17.625 * temperature) / (243.03 + temperature);

                        // calculate value B
                        // uses the same formula, but with the dewpoint instead
                        // E = (17.625 * dewpoint) / (243.03 + dewpoint)
                        Double B = (17.625 * dewpoint) / (243.03 + dewpoint);

                        // calculate the relative humidity with both values
                        // Rh = (e^B) / (e^A) * 100
                        Double humidity = (Math.exp(B) / Math.exp(A)) * 100;

                        String day_padded = String.format("%02d", day);
                        String month_padded = String.format("%02d", month);
                        String year_padded = String.format("%02d", year);
                        String hour_padded = String.format("%02d", hour);

                        String temp_padded = String.format("%+05.1f", temperature);
                        String humidity_padded = String.format("%06.2f", humidity);
                        String rain_padded = String.format("%06.2f", rainfall);

                        String final_format =  year_padded + month_padded + day_padded + hour_padded + nr + temp_padded + humidity_padded + rain_padded;
                        //System.out.println(final_format);
                        socket.SendData(final_format);
                        station.Reset();
                    }
                }
                catch (ConcurrentModificationException e) {
                    e.printStackTrace();
                }
                GetCurrentTime();
            } catch (InterruptedException e) {
                e.printStackTrace();
            }    
            finally{
                socket.CloseSocket();
            }
        }
    }

    public void AddData(WeatherStation ws) {
        WeatherStationWrapper wsw = null;
        if ((wsw = data.get(ws.id)) == null) {
            wsw = new WeatherStationWrapper();
            data.put(ws.id, wsw);
        }
        wsw.Add(ws);
    }

    private void GetCurrentTime() {
        ldt = LocalDateTime.now();
        year = ldt.getYear() % 100;
        month = ldt.getMonthValue();
        day = ldt.getDayOfMonth();
        hour = ldt.getHour();
        minute = ldt.getMinute();
        second = ldt.getSecond();
    }
}
