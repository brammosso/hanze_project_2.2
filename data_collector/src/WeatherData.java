import java.io.IOException;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;

public class WeatherData extends Thread {

    private DateTimeFormatter dtf;
    private LocalDateTime ldt;
    private int year;
    private int month;
    private int day;
    private int hour;
    private int minute;
    private int second;

    private SocketClient socket = new SocketClient("127.0.0.1", 7790);

    private HashMap<Integer, WeatherStationWrapper> data;

    private Thread thread = null;

    public WeatherData() {
        data = new HashMap<Integer, WeatherStationWrapper>();
        dtf = DateTimeFormatter.ofPattern("yyyy/MM/dd HH:mm:ss");
        GetCurrentTime();
    }

    public void start() {
        System.out.println("Thread starting");
        if (thread == null) {
            thread = new Thread(this);
            thread.start();
        }
    }

    public void run() {
        GetCurrentTime();
        while (true) {
            try {
                // Thread.sleep(1000*(((59-minute)*60)+(60-second)));
                Thread.sleep(2000);

                socket.OpenSocket();
                
                Iterator stations_it = new HashMap<Integer, WeatherStationWrapper>(data).entrySet().iterator();

                // loop through the hashmap containing all data
                while (stations_it.hasNext()) {
                    Map.Entry<Integer, WeatherStationWrapper> pair = (Map.Entry) stations_it.next();
                    WeatherStationWrapper station = pair.getValue();

                    // extract data from the weatherstation object
                    String nr = String.format("%06d", pair.getKey());
                    Float temperature = station.weatherStation.temperature;
                    Float dewpoint = station.weatherStation.dewPoint;
                    Float rainfall = station.weatherStation.rainfall;

                    String day_padded = String.format("%02d", day);
                    String month_padded = String.format("%02d", month);
                    String year_padded = String.format("%02d", year);
                    String hour_padded = String.format("%02d", hour);

                    String temp_padded = String.format("%+05.1f", temperature);
                    String dewp_padded = String.format("%+05.1f", dewpoint);
                    String rain_padded = String.format("%+06.2f", rainfall);

                    String final_format = nr + day_padded + month_padded + year_padded + hour_padded + temp_padded
                            + dewp_padded + rain_padded;
                    System.out.println(final_format);
                    socket.SendData(final_format);
                    station.Reset();
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
        /*System.out.println("Year: " + year);
        System.out.println("month: " + month);
        System.out.println("day: " + day);
        System.out.println("hour: " + hour);
        System.out.println("minute: " + minute);
        System.out.println("second: " + second);*/
    }
}
