import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;

public class Humidity {

    private File file;
    private FileOutputStream oFile;

    public Humidity() {
        try {
            file = new File("/data/humidity.txt");
            oFile = new FileOutputStream(file, true);
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        }
    }

    public void Write(String hour, String day, String month, String year, String nr, String humidity) {
        try {
            oFile.write((hour+day+month+year+nr+humidity+"\n").getBytes());
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
