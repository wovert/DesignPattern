# Strategy 策略模式

## 对于一个新手的代码如下

``` java
package strategy;  
  
public class Example {  
  
  /**
    *  
    *描述：计算用户所付金额
    */  
  public Double calRecharge(Double charge ,RechargeTypeEnum type ){  
    if(type.equals(RechargeTypeEnum.E_BANK)){  
      return charge*0.85;  
    }else if(type.equals(RechargeTypeEnum.BUSI_ACCOUNTS)){  
      return charge*0.90;  
    }else if(type.equals(RechargeTypeEnum.MOBILE)){  
      return charge;  
    }else if(type.equals(RechargeTypeEnum.CARD_RECHARGE)){  
      return charge+charge*0.01;  
    }else{  
      return null;  
    }  

  }
}
```

``` java
package strategy;  
  
public enum RechargeTypeEnum {  
  
  E_BANK(1, "网银"),  
  BUSI_ACCOUNTS(2, "商户账号"),  
  MOBILE(3,"手机卡充值"),  
  CARD_RECHARGE(4,"充值卡");  

  /**
    * 状态值
    */  
  private int value;  

  /**
    * 类型描述
    */  
  private String description;  


  private RechargeTypeEnum(int value, String description) {  
    this.value = value;  
    this.description = description;  
  }  

  public int value() {  
    return value;  
  }  
  public String description() {  
    return description;  
  }  

  public static RechargeTypeEnum valueOf(int value) {  
    for(RechargeTypeEnum type : RechargeTypeEnum.values()) {  
      if(type.value() == value) {  
        return type;  
      }  
    }  
    return null;
  }  
}
```

可以看出上面四种不同的计算方式在一个方法内部，不利于扩展和维护，当然也不符合面向对象设计原则。对以上的代码利用策略模式进行修改，类图如下：

![strategy](./images/strategy-opt.png)

``` java
package strategy.strategy;  
  
import strategy.RechargeTypeEnum;  
  
/**
 *  
 *描述：策略抽象类
 */  
public interface Strategy {  
  
  /**
    *  
    *描述：策略行为方法
    */  
  public Double calRecharge(Double charge ,RechargeTypeEnum type );  
}
```

``` java
package strategy.strategy;  
  
import strategy.RechargeTypeEnum;  
/**
 *  
 *描述：网银充值
 */  
public class EBankStrategy implements Strategy{  
  
    @Override  
    public Double calRecharge(Double charge, RechargeTypeEnum type) {  
        return charge*0.85;  
    }  
}
```

``` java
package strategy.strategy;  
  
import strategy.RechargeTypeEnum;  
/**
 *  
 *描述：商户账号充值
 */  
public class BusiAcctStrategy implements Strategy{  
  
  @Override  
  public Double calRecharge(Double charge, RechargeTypeEnum type) {  
    // TODO Auto-generated method stub  
    return charge*0.90;  
  }  
  
}  
```

``` java
package strategy.strategy;  
  
import strategy.RechargeTypeEnum;  
/**
 *  
 *描述：手机充值
 */  
public class MobileStrategy implements Strategy {  
  
  @Override  
  public Double calRecharge(Double charge, RechargeTypeEnum type) {  
    // TODO Auto-generated method stub  
    return charge;  
  }  
}  
```

``` java
package strategy.strategy;  
  
import strategy.RechargeTypeEnum;  
/**
 *  
 *描述：充值卡充值
 */  
public class CardStrategy implements Strategy{  

  @Override  
  public Double calRecharge(Double charge, RechargeTypeEnum type) {  
      return charge+charge*0.01;  
  }  
  
}
```

``` java
package strategy.strategy;  
  
import strategy.RechargeTypeEnum;  
  
/**
 *  
 *描述：场景类
 */  
public class Context {  
  
  private Strategy strategy;  

  public Double calRecharge(Double charge, Integer type) {  
      strategy = StrategyFactory.getInstance().creator(type);  
      return strategy.calRecharge(charge, RechargeTypeEnum.valueOf(type));  
  }  

  public Strategy getStrategy() {  
      return strategy;  
  }  

  public void setStrategy(Strategy strategy) {  
      this.strategy = strategy;  
  }
}  
```

``` java
package strategy.strategy;  
  
import java.util.HashMap;  
import java.util.Map;  
  
import strategy.RechargeTypeEnum;  
/**
 *  
 *描述：策略工厂 使用单例模式
 */  
public class StrategyFactory {  
  
    private static StrategyFactory factory = new StrategyFactory();  
    private StrategyFactory() {}

    private static Map<Integer ,Strategy> strategyMap = new HashMap<>();  

    static {  
        strategyMap.put(RechargeTypeEnum.E_BANK.value(), new EBankStrategy());  
        strategyMap.put(RechargeTypeEnum.BUSI_ACCOUNTS.value(), new BusiAcctStrategy());  
        strategyMap.put(RechargeTypeEnum.MOBILE.value(), new MobileStrategy());  
        strategyMap.put(RechargeTypeEnum.CARD_RECHARGE.value(), new CardStrategy());  
    }  

    public Strategy creator(Integer type){  
        return strategyMap.get(type);  
    }  

    public static StrategyFactory getInstance(){  
        return factory;  
    }  
}
```

``` java
package strategy.strategy;  
  
import strategy.RechargeTypeEnum;  
  
public class Client {  
  
  public static void main(String[] args) {  

    Context context = new Context();  

    // 网银充值100 需要付多少  
    Double money = context.calRecharge(100D,  
      RechargeTypeEnum.E_BANK.value());  
    System.out.println(money);  

    // 商户账户充值100 需要付多少  
    Double money2 = context.calRecharge(100D,  
      RechargeTypeEnum.BUSI_ACCOUNTS.value());  
    System.out.println(money2);  

    // 手机充值100 需要付多少  
    Double money3 = context.calRecharge(100D,  
      RechargeTypeEnum.MOBILE.value());  
    System.out.println(money3);  

    // 充值卡充值100 需要付多少  
    Double money4 = context.calRecharge(100D,  
      RechargeTypeEnum.CARD_RECHARGE.value());  
    System.out.println(money4);  
  }  
  
}

输出结构
85.0
90.0
100.0
101.0

```

从上面类图和代码可以看出，策略模式把具体的算法封装到了具体策略角色内部，增强了可扩展性，隐蔽了实现细节；它替代继承来实现，避免了if-else这种不易维护的条件语句。当然我们也可以看到，策略模式由于独立策略实现，使得系统内增加了很多策略类；对客户端来说必须知道兜友哪些具体策略，而且需要知道选择具体策略的条件。