import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;

public class Rainfall {

    public Rainfall() {

    }

    public void Write(String hour, String day, String month, String year, String nr, String rainfall) {
        try {
            String dir = ("./data/rainfall/20" + year + "-" + month + "-" + day);
            Files.createDirectories(Paths.get(dir));
            File file = new File((dir + "/" + nr + ".txt"));
            FileOutputStream oFile = new FileOutputStream(file, true);
            oFile.write((hour+rainfall+"\n").getBytes());
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
