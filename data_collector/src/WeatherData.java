import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;

public class WeatherData extends Thread {

    private DateTimeFormatter dtf;
    private LocalDateTime ldt;
    private byte year;
    private byte month;
    private byte day;
    private byte hour;
    private byte minute;
    private byte second;

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
        while (true) {
            try {
                //Thread.sleep(1000*(((59-minute)*60)+(60-second)));
                Thread.sleep(2000);
                Iterator stations_it = data.entrySet().iterator();
                
                Integer year_int = ldt.getYear() % 100;
                Integer month_int = ldt.getMonthValue();
                Integer day_int = ldt.getDayOfMonth();
                Integer hour = ldt.getHour();

                // loop through the hashmap containing all data
                while(stations_it.hasNext()){
                    Map.Entry<Integer, WeatherStationWrapper> pair = (Map.Entry)stations_it.next();
                    WeatherStationWrapper station = pair.getValue();

                    // extract data from the weatherstation object
                    String nr = String.format("%06d", pair.getKey());
                    Float temperature = station.weatherStation.temperature;
                    Float dewpoint = station.weatherStation.dewPoint;
                    Float rainfall = station.weatherStation.rainfall;

                    String day_padded = String.format("%02d", day_int);
                    String month_padded = String.format("%02d", month_int);
                    String year_padded = String.format("%02d", year_int);
                    String hour_padded = String.format("%02d", hour);

                    String temp_padded = String.format("%+05.1f", temperature);
                    String dewp_padded = String.format("%+05.1f", dewpoint);
                    String rain_padded = String.format("%+06.2f", rainfall);

                    String final_format = nr + day_padded + month_padded + year_padded + hour_padded + temp_padded + dewp_padded + rain_padded;
                    System.out.println(final_format);
                    station.Reset();
                }
                GetCurrentTime();
            } catch (InterruptedException e) {
                e.printStackTrace();
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
        year = Byte.parseByte(Integer.toString(ldt.getYear()).substring(2, 4));
        month = Byte.parseByte(Integer.toString(ldt.getMonthValue()));
        day = Byte.parseByte(Integer.toString(ldt.getDayOfMonth()));
        hour = Byte.parseByte(Integer.toString(ldt.getHour()));
        minute = Byte.parseByte(Integer.toString(ldt.getMinute()));
        second = Byte.parseByte(Integer.toString(ldt.getSecond()));
        /*System.out.println("Year: " + year);
        System.out.println("month: " + month);
        System.out.println("day: " + day);
        System.out.println("hour: " + hour);
        System.out.println("minute: " + minute);
        System.out.println("second: " + second);*/
    }
}
